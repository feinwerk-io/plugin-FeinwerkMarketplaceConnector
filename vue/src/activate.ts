/** Matomo's own activate link (Controller::activateUrl) for one plugin; '' when not allowed. */
export default function activateLink(base: string, slug: string): string {
  return base ? `${base}&pluginName=${encodeURIComponent(slug)}` : '';
}
