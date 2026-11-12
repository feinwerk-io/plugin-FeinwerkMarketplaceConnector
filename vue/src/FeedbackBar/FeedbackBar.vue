<!--
  Feinwerk feedback kit (docs/feedback-kit.md of FeinwerkEmailReportsPro): a question with
  thumbs up and down under the screen, sent through the connector's sendFeedback action (the
  Matomo server talks to Feinwerk, never the browser), and a support link in a new tab. Never
  inside the plugin window: Matomo's dialog turns [role] elements there into empty buttons.
-->
<template>
  <!-- One compact line (Peter, 2026-11-11; reference PageFilterReport b628e33): question, thumbs,
       support link; after a vote the thanks takes the place of question and thumbs. -->
  <div
    v-if="feedback.enabled || feedback.supportUrl"
    class="fwmc-feedback"
    :class="{ 'is-voted': vote !== '' }"
  >
    <template v-if="feedback.enabled">
      <span class="fwmc-feedback-question">{{ question }}</span>
      <button
        v-for="choice in choices"
        :key="choice"
        type="button"
        class="fwmc-feedback-thumb"
        :title="label(choice)"
        :aria-label="label(choice)"
        :aria-pressed="vote === choice ? 'true' : 'false'"
        @click="send(choice)"
      >
        <svg
          viewBox="0 0 24 24"
          width="14"
          height="14"
          aria-hidden="true"
          focusable="false"
          :class="`fwmc-feedback-icon-${choice}`"
        >
          <path :d="thumbPath" />
        </svg>
      </button>
      <span class="fwmc-feedback-thanks" role="status">{{ thanks }}</span>
    </template>
    <a
      v-if="feedback.supportUrl"
      class="fwmc-feedback-support"
      :href="feedback.supportUrl"
      target="_blank"
      rel="noopener noreferrer"
      :title="tr('SupportTitle')"
    >{{ tr('Support') }}</a>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { translate } from 'CoreHome';

const PLUGIN = 'FeinwerkMarketplaceConnector';

/** a thumb outline (24 x 24); the down thumb is the same, rotated */
const THUMB_PATH = 'M3 10h4v10H3zM7 10l4-7c1.3 0 2.2 1.1 2 2.4L12.4 9H19a2 2 0 0 1 2 2.3l-1.2 7A2 2 0 0 1 17.8 20H7';

type Vote = 'up' | 'down';

/** from Controller::index: switch, support URL, connector version */
export interface FeedbackSettings {
  enabled: boolean;
  supportUrl: string;
  version: string;
}

/** localStorage: this browser's vote per question and connector version (a convenience only) */
function storageKey(topic: string): string {
  return `fwmc.feedback.${topic}`;
}

function storedVote(topic: string, version: string): '' | Vote {
  try {
    const stored = JSON.parse(window.localStorage.getItem(storageKey(topic)) || '{}');
    return stored.version === version && (stored.vote === 'up' || stored.vote === 'down')
      ? stored.vote : '';
  } catch (e) {
    return '';
  }
}

export default defineComponent({
  props: {
    /** the store "topic": one of Core/FeedbackVote::TOPICS */
    topic: { type: String, required: true },
    /** the question, already translated */
    question: { type: String, required: true },
    nonce: { type: String, required: true },
    feedback: {
      type: Object as PropType<FeedbackSettings>,
      default: () => ({ enabled: false, supportUrl: '', version: '' }),
    },
  },
  data() {
    return { vote: storedVote(this.topic, this.feedback.version) as '' | Vote };
  },
  computed: {
    choices(): Vote[] {
      return ['up', 'down'];
    },
    thumbPath(): string {
      return THUMB_PATH;
    },
    thanks(): string {
      if (!this.vote) {
        return '';
      }
      return this.vote === 'up' ? this.tr('FeedbackThanks') : this.tr('FeedbackThanksDown');
    },
  },
  methods: {
    tr(key: string): string {
      return translate(`${PLUGIN}_${key}`);
    },
    label(choice: Vote): string {
      return choice === 'up' ? this.tr('FeedbackYes') : this.tr('FeedbackNo');
    },
    send(vote: Vote) {
      if (this.vote) {
        return;
      }
      this.vote = vote;
      try {
        const stored = { version: this.feedback.version, vote };
        window.localStorage.setItem(storageKey(this.topic), JSON.stringify(stored));
      } catch (e) {
        // private mode or blocked storage: the thanks is shown anyway
      }
      // Best effort: the vote is a hint for Feinwerk, a failed request needs no error message.
      const body = new URLSearchParams({ nonce: this.nonce, topic: this.topic, vote });
      fetch(`index.php?module=${PLUGIN}&action=sendFeedback`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', Accept: 'application/json' },
        body,
      }).catch(() => undefined);
    },
  },
});
</script>
