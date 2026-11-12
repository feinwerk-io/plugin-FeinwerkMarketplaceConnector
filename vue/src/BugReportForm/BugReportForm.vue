<template>
  <div class="fwmc-bugreport">
    <a href="#" @click.prevent="open = !open" class="fwmc-bugreport-toggle">
      {{ translate('FeinwerkMarketplaceConnector_ReportBug') }}
    </a>

    <form v-if="open" class="fwmc-bugreport-form" @submit.prevent="submit">
      <label>
        {{ translate('FeinwerkMarketplaceConnector_ReportBugTitle') }}
        <input type="text" v-model.trim="title" maxlength="120" :disabled="sending" />
      </label>
      <label>
        {{ translate('FeinwerkMarketplaceConnector_ReportBugDescription') }}
        <textarea
          v-model.trim="description"
          rows="4"
          maxlength="5000"
          :disabled="sending"
        ></textarea>
      </label>
      <label>
        {{ translate('FeinwerkMarketplaceConnector_ReportBugScreenshots') }}
        <input
          type="file"
          accept="image/png,image/jpeg,image/webp"
          multiple
          :disabled="sending"
          @change="pickFiles"
        />
      </label>
      <ul v-if="files.length" class="fwmc-bugreport-files">
        <li v-for="f in files" :key="f.name">{{ f.name }} ({{ Math.round(f.size / 1024) }} KB)</li>
      </ul>

      <div class="alert alert-success" v-if="successMessage">{{ successMessage }}</div>
      <div class="alert alert-warning" v-if="errorMessage">{{ errorMessage }}</div>

      <button type="submit" class="btn" :disabled="sending">
        {{ translate(sending
          ? 'FeinwerkMarketplaceConnector_ReportBugSending'
          : 'FeinwerkMarketplaceConnector_ReportBugSubmit') }}
      </button>
    </form>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { translate } from 'CoreHome';

const MAX_FILES = 3;
const MAX_BYTES = 4 * 1024 * 1024;
const TYPES = ['image/png', 'image/jpeg', 'image/webp'];

export default defineComponent({
  props: {
    slug: { type: String, required: true },
    nonce: { type: String, required: true },
  },
  data() {
    return {
      open: false,
      title: '',
      description: '',
      files: [] as File[],
      sending: false,
      successMessage: null as string|null,
      errorMessage: null as string|null,
    };
  },
  methods: {
    translate,
    pickFiles(event: Event) {
      this.errorMessage = null;
      const list = Array.from((event.target as HTMLInputElement).files || []);
      if (list.length > MAX_FILES) {
        this.errorMessage = translate('FeinwerkMarketplaceConnector_ReportBugTooMany');
        this.files = [];
        return;
      }
      // The first file that does not fit, in the order picked: wrong type, else too large.
      const bad = list.find((f) => !TYPES.includes(f.type) || f.size > MAX_BYTES);
      if (bad) {
        this.errorMessage = translate(
          TYPES.includes(bad.type)
            ? 'FeinwerkMarketplaceConnector_ReportBugFileTooLarge'
            : 'FeinwerkMarketplaceConnector_ReportBugFileWrongType',
          bad.name,
        );
        this.files = [];
        return;
      }
      this.files = list;
    },
    async post(action: string, fields: Record<string, string>): Promise<any> {
      const body = new URLSearchParams({ nonce: this.nonce, ...fields });
      const response = await fetch(`index.php?module=FeinwerkMarketplaceConnector&action=${action}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', Accept: 'application/json' },
        body,
      });
      return response.json();
    },
    fileToBase64(file: File): Promise<string> {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve((reader.result as string).split(',', 2)[1] || '');
        reader.onerror = reject;
        reader.readAsDataURL(file);
      });
    },
    async submit() {
      this.successMessage = null;
      this.errorMessage = null;
      if (!this.title || !this.description) {
        this.errorMessage = translate('FeinwerkMarketplaceConnector_ReportBugRequired');
        return;
      }
      this.sending = true;
      try {
        const created = await this.post('reportBug', {
          slug: this.slug, title: this.title, description: this.description,
        });
        if (!created.ok) {
          this.errorMessage = translate('FeinwerkMarketplaceConnector_ReportBugFailed', created.error || '');
          return;
        }
        const failed: string[] = [];
        // One screenshot per request, one after the other: no request bursts the PHP body limits.
        for (let i = 0; i < this.files.length; i += 1) {
          const f = this.files[i];
          // eslint-disable-next-line no-await-in-loop
          const b64 = await this.fileToBase64(f);
          // eslint-disable-next-line no-await-in-loop
          const attached = await this.post('reportBugScreenshot', {
            id: String(created.id), image_base64: b64, mime: f.type,
          });
          if (!attached.ok) failed.push(f.name);
        }
        if (failed.length) {
          this.errorMessage = translate('FeinwerkMarketplaceConnector_ReportBugAttachFailed', failed.join(', '));
        } else {
          this.successMessage = translate('FeinwerkMarketplaceConnector_ReportBugSuccess');
          this.title = ''; this.description = ''; this.files = [];
        }
      } catch (e) {
        this.errorMessage = translate('FeinwerkMarketplaceConnector_ReportBugFailed', String(e));
      } finally {
        this.sending = false;
      }
    },
  },
});
</script>
