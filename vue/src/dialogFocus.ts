// Keyboard handling of the connector's own dialogs (database change; UI review 2026-11-23, F9):
// focus moves into the dialog, Tab stays inside it, Escape closes it, and focus returns to the
// opener. The dialogs are teleported to <body>, outside the plugin window's Materialize modal,
// which pulls focus back into itself on every focus event (5.0 to 6). A capture listener on
// window runs before the modal's one on document and stops it for targets inside the dialog.
const FOCUSABLE = 'button:not([disabled]), [href], input:not([disabled]), select, textarea, [tabindex]:not([tabindex="-1"])';

export default class DialogFocus {
  private dialog: HTMLElement|null = null;

  private opener: HTMLElement|null = null;

  private close: () => void = () => undefined;

  private readonly onFocus = (event: FocusEvent): void => {
    if (this.dialog && event.target instanceof Node && this.dialog.contains(event.target)) {
      event.stopPropagation();
    }
  };

  private readonly onKey = (event: KeyboardEvent): void => {
    if (!this.dialog) return;
    if (event.key === 'Escape') {
      event.stopPropagation();
      event.preventDefault();
      this.close();
      return;
    }
    if (event.key !== 'Tab') return;
    const items = Array.from(this.dialog.querySelectorAll<HTMLElement>(FOCUSABLE));
    if (!items.length) return;
    const first = items[0];
    const last = items[items.length - 1];
    const active = document.activeElement;
    if (event.shiftKey && (active === first || !this.dialog.contains(active))) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && (active === last || !this.dialog.contains(active))) {
      event.preventDefault();
      first.focus();
    }
  };

  open(dialog: HTMLElement, start: HTMLElement|null, close: () => void): void {
    this.release(false);
    this.dialog = dialog;
    this.close = close;
    this.opener = document.activeElement instanceof HTMLElement ? document.activeElement : null;
    window.addEventListener('focus', this.onFocus, true);
    document.addEventListener('keydown', this.onKey, true);
    const target = start || dialog.querySelector<HTMLElement>(FOCUSABLE);
    if (target) target.focus();
  }

  release(restore = true): void {
    if (!this.dialog) return;
    window.removeEventListener('focus', this.onFocus, true);
    document.removeEventListener('keydown', this.onKey, true);
    this.dialog = null;
    if (restore && this.opener && document.contains(this.opener)) {
      this.opener.focus();
    }
    this.opener = null;
  }
}
