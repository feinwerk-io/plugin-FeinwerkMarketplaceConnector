// Where the admin is on the Feinwerk Plugins screen. Every action form sends it right before it
// goes out (fwmc_tab, fwmc_return_open), so the server brings them back to the same tab and plugin
// window (Controller::backToScreen).

export const TAB_MANAGE = 'verwaltung';

// The tab the screen shows (PluginsPage sets data-tab; one action per tab since 5.1.0).
export function currentTab(): string {
  const page = document.querySelector('.fwmc[data-tab]') as HTMLElement|null;
  return page ? String(page.dataset.tab || '') : '';
}

function setField(form: HTMLFormElement, name: string, value: string): void {
  let input = form.querySelector(`input[name="${name}"]`) as HTMLInputElement|null;
  if (!input) {
    input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    form.appendChild(input);
  }
  input.value = value;
}

// returnOpen: the plugin whose window the form sits in, '' outside a window.
export function addActionContext(form: HTMLFormElement, returnOpen = ''): void {
  setField(form, 'fwmc_tab', currentTab());
  setField(form, 'fwmc_return_open', returnOpen);
}

// One action at a time on the whole screen: the forms share one nonce, so a second form sent before
// the page changes would end in Matomo's security-check error. Reset by leaving the page (a page
// restored from the browser's back cache reloads, PluginsPage).
let sent = false;

// Whether this page has sent an action (the page is about to change).
export function submitted(): boolean {
  return sent;
}

export function claimSubmit(): boolean {
  if (sent) {
    return false;
  }
  sent = true;
  return true;
}
