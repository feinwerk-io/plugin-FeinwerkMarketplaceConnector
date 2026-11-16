<template>
  <!-- Feinwerk Plugins logo: the markup of templates/_feinwerkLogo.twig (copied byte-identical
       from the promo kit, Page Filter Report 089a320), one tag per line; styles in
       stylesheets/feinwerklogo.css. HarnessTest keeps both equal. -->
  <span class="fwlogo" aria-hidden="true">
    <svg class="fwlogo__mark" viewBox="0 0 48 48" width="18" height="18" focusable="false">
      <circle class="fwlogo__core" cx="24" cy="24" r="5.4"/>
      <g class="fwlogo__dots" opacity="0.9">
        <circle cx="16" cy="24" r="2.9"/>
        <circle cx="32" cy="24" r="2.9"/>
        <circle cx="24" cy="16" r="2.9"/>
        <circle cx="24" cy="32" r="2.9"/>
      </g>
      <g class="fwlogo__dots" opacity="0.55">
        <circle cx="16" cy="16" r="2.5"/>
        <circle cx="32" cy="16" r="2.5"/>
        <circle cx="16" cy="32" r="2.5"/>
        <circle cx="32" cy="32" r="2.5"/>
      </g>
      <g class="fwlogo__dots" opacity="0.35">
        <circle cx="8" cy="24" r="2.1"/>
        <circle cx="40" cy="24" r="2.1"/>
        <circle cx="24" cy="8" r="2.1"/>
        <circle cx="24" cy="40" r="2.1"/>
      </g>
    </svg>
    <span class="fwlogo__word">feinwerk<span class="fwlogo__sub"> plugins</span></span>
  </span>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({});
</script>
