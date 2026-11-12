(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory(require("CoreHome"), require("vue"));
	else if(typeof define === 'function' && define.amd)
		define(["CoreHome", ], factory);
	else if(typeof exports === 'object')
		exports["FeinwerkMarketplaceConnector"] = factory(require("CoreHome"), require("vue"));
	else
		root["FeinwerkMarketplaceConnector"] = factory(root["CoreHome"], root["Vue"]);
})((typeof self !== 'undefined' ? self : this), function(__WEBPACK_EXTERNAL_MODULE__19dc__, __WEBPACK_EXTERNAL_MODULE__8bbf__) {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "plugins/FeinwerkMarketplaceConnector/vue/dist/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "fae3");
/******/ })
/************************************************************************/
/******/ ({

/***/ "19dc":
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE__19dc__;

/***/ }),

/***/ "8bbf":
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE__8bbf__;

/***/ }),

/***/ "fae3":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, "PluginsPage", function() { return /* reexport */ PluginsPage; });
__webpack_require__.d(__webpack_exports__, "PromoBanner", function() { return /* reexport */ PromoBanner; });

// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js
// This file is imported into lib/wc client bundles.

if (typeof window !== 'undefined') {
  var currentScript = window.document.currentScript
  if (false) { var getCurrentScript; }

  var src = currentScript && currentScript.src.match(/(.+\/)[^/]+\.js(\?.*)?$/)
  if (src) {
    __webpack_require__.p = src[1] // eslint-disable-line
  }
}

// Indicate to webpack that this file can be concatenated
/* harmony default export */ var setPublicPath = (null);

// EXTERNAL MODULE: external {"commonjs":"vue","commonjs2":"vue","root":"Vue"}
var external_commonjs_vue_commonjs2_vue_root_Vue_ = __webpack_require__("8bbf");

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/PluginsPage/PluginsPage.vue?vue&type=template&id=1f1a1b4b

const _hoisted_1 = ["data-tab"];
const _hoisted_2 = {
  class: "fwmc-head"
};
const _hoisted_3 = {
  key: 0,
  class: "fwmc-head-actions"
};
const _hoisted_4 = {
  key: 0,
  class: "fwmc-meta"
};
const _hoisted_5 = ["action"];
const _hoisted_6 = ["value"];
const _hoisted_7 = ["disabled"];
const _hoisted_8 = {
  class: "fwmc-connect"
};
const _hoisted_9 = ["action"];
const _hoisted_10 = ["value"];
const _hoisted_11 = {
  type: "submit",
  class: "btn"
};
const _hoisted_12 = {
  class: "fwmc-meta"
};
const _hoisted_13 = ["href"];
const _hoisted_14 = {
  key: 0,
  class: "fwmc-browse-offer"
};
const _hoisted_15 = ["action"];
const _hoisted_16 = ["value"];
const _hoisted_17 = ["disabled"];
const _hoisted_18 = {
  class: "fwmc-meta"
};
const _hoisted_19 = {
  key: 0,
  class: "fwmc-public"
};
const _hoisted_20 = {
  class: "fwmc-meta fwmc-browse-intro"
};
const _hoisted_21 = ["action"];
const _hoisted_22 = ["value"];
const _hoisted_23 = ["disabled"];
const _hoisted_24 = {
  key: 1,
  class: "alert alert-warning"
};
const _hoisted_25 = {
  key: 0,
  class: "fwmc-note",
  "aria-live": "polite"
};
const _hoisted_26 = {
  key: 1,
  class: "fwmc-selfupdate"
};
const _hoisted_27 = ["action"];
const _hoisted_28 = ["value"];
const _hoisted_29 = ["value"];
const _hoisted_30 = ["value"];
const _hoisted_31 = ["disabled"];
const _hoisted_32 = {
  key: 2,
  class: "fwmc-note"
};
const _hoisted_33 = ["href"];
const _hoisted_34 = ["action"];
const _hoisted_35 = ["value"];
const _hoisted_36 = {
  type: "submit",
  class: "btn"
};
const _hoisted_37 = {
  class: "fwmc-tabs"
};
const _hoisted_38 = ["href", "aria-current"];
function render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_BrowseGrid = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("BrowseGrid");
  const _component_TaskList = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("TaskList");
  const _component_PromoBanner = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("PromoBanner");
  const _component_LicenseTable = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("LicenseTable");
  const _component_ManageTable = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("ManageTable");
  const _component_FeedbackBar = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("FeedbackBar");
  const _component_DetailModal = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("DetailModal");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", {
    class: "fwmc",
    "data-tab": _ctx.tab
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_2, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h2", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_MenuTitle')), 1)]), !_ctx.error && _ctx.isConnected ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_3, [_ctx.lastUpdated ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", _hoisted_4, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_Updated', _ctx.lastUpdated)), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("form", {
    method: "post",
    action: _ctx.refreshUrl,
    onSubmit: _cache[0] || (_cache[0] = $event => _ctx.onFormSubmit($event, 'refresh'))
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "nonce",
    value: _ctx.formNonce
  }, null, 8, _hoisted_6), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "submit",
    class: "btn",
    disabled: _ctx.busyForm === 'refresh'
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.busyForm === 'refresh' ? _ctx.translate('FeinwerkMarketplaceConnector_Working') : _ctx.translate('FeinwerkMarketplaceConnector_Refresh')), 9, _hoisted_7)], 40, _hoisted_5)])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), !_ctx.isConnected ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
    key: 0
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_8, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h3", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ConnectTitle')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ConnectIntro')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("form", {
    method: "post",
    action: _ctx.connectUrl
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "nonce",
    value: _ctx.formNonce
  }, null, 8, _hoisted_10), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", _hoisted_11, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ConnectButton')), 1)], 8, _hoisted_9), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", _hoisted_12, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ConnectManual')) + " ", 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("a", {
    href: _ctx.settingsUrl
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ConnectManualLink')), 9, _hoisted_13)]), _ctx.browseInfo.url && !_ctx.browseInfo.loaded ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_14, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("form", {
    method: "post",
    action: _ctx.browseInfo.url,
    onSubmit: _cache[1] || (_cache[1] = $event => _ctx.onFormSubmit($event, 'browse'))
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "nonce",
    value: _ctx.formNonce
  }, null, 8, _hoisted_16), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "submit",
    class: "btn btn-outline",
    disabled: _ctx.busyForm === 'browse'
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate(_ctx.busyForm === 'browse' ? 'FeinwerkMarketplaceConnector_Working' : 'FeinwerkMarketplaceConnector_BrowseButton')), 9, _hoisted_17)], 40, _hoisted_15), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", _hoisted_18, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_BrowseHint')), 1)])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), _ctx.browseInfo.loaded ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_19, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_20, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_BrowseIntro', _ctx.browseInfo.fetchedAt || '')) + " ", 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("form", {
    method: "post",
    action: _ctx.browseInfo.url,
    onSubmit: _cache[2] || (_cache[2] = $event => _ctx.onFormSubmit($event, 'browse'))
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "nonce",
    value: _ctx.formNonce
  }, null, 8, _hoisted_22), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "submit",
    class: "fwmc-textbtn",
    disabled: _ctx.busyForm === 'browse'
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate(_ctx.busyForm === 'browse' ? 'FeinwerkMarketplaceConnector_Working' : 'FeinwerkMarketplaceConnector_BrowseRefresh')), 9, _hoisted_23)], 40, _hoisted_21)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_BrowseGrid, {
    rows: _ctx.browseRows,
    onOpen: _ctx.openStorePage
  }, null, 8, ["rows", "onOpen"])])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)], 64)) : _ctx.error ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_24, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.error), 1)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
    key: 2
  }, [_ctx.stateChanged ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_25, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_StateChanged')) + " ", 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("a", {
    href: "",
    onClick: _cache[3] || (_cache[3] = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])((...args) => _ctx.reload && _ctx.reload(...args), ["prevent"]))
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_StateReload')), 1)])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.selfUpdate ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_26, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_SelfUpdateAvailable', _ctx.selfUpdate.installedVersion, _ctx.selfUpdate.latestVersion)), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("form", {
    method: "post",
    action: _ctx.updateUrl,
    onSubmit: _cache[4] || (_cache[4] = $event => _ctx.onFormSubmit($event, 'self'))
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "nonce",
    value: _ctx.formNonce
  }, null, 8, _hoisted_28), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "slug",
    value: _ctx.selfUpdate.slug
  }, null, 8, _hoisted_29), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "version",
    value: _ctx.selfUpdate.latestVersion
  }, null, 8, _hoisted_30), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "submit",
    class: "btn",
    disabled: _ctx.busyForm === 'self'
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.busyForm === 'self' ? _ctx.translate('FeinwerkMarketplaceConnector_Working') : _ctx.translate('FeinwerkMarketplaceConnector_UpdateTo', _ctx.selfUpdate.latestVersion)), 9, _hoisted_31)], 40, _hoisted_27)])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.refreshError ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_32, [_ctx.refreshErrorKind === 'offline' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
    key: 0
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_OfflineNote')) + " ", 1), _ctx.safeOfflineDocsUrl ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
    key: 0,
    href: _ctx.safeOfflineDocsUrl,
    target: "_blank",
    rel: "noreferrer noopener"
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_OfflineLink')) + " ↗ ", 9, _hoisted_33)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)], 64)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
    key: 1
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate(_ctx.refreshErrorKind === 'auth' ? 'FeinwerkMarketplaceConnector_AuthErrorNote' : 'FeinwerkMarketplaceConnector_CachedNote')), 1)], 64)), _ctx.refreshErrorKind === 'auth' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("form", {
    key: 2,
    method: "post",
    action: _ctx.connectUrl,
    class: "fwmc-note-action"
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "nonce",
    value: _ctx.formNonce
  }, null, 8, _hoisted_35), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", _hoisted_36, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ConnectAgain')), 1)], 8, _hoisted_34)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("ul", _hoisted_37, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.tabs, t => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("li", {
      key: t.id,
      class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])({
        active: _ctx.tab === t.id
      })
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("a", {
      href: t.url,
      "aria-current": _ctx.tab === t.id ? 'page' : null
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate(t.label)), 1), t.id === 'aufgaben' && _ctx.actionItems.length ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 0
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" (" + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.actionItems.length) + ")", 1)], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)], 8, _hoisted_38)], 2);
  }), 128))]), _ctx.tab === 'aufgaben' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_TaskList, {
    key: 3,
    items: _ctx.actionItems,
    rows: _ctx.rows,
    nonce: _ctx.formNonce,
    "update-url": _ctx.updateUrl,
    "claim-url": _ctx.claimUrl,
    "buy-url": _ctx.buyUrl,
    "connect-url": _ctx.connectUrl,
    "switch-now-url": _ctx.switchNowUrl,
    "account-url": _ctx.accountUrl,
    "offline-docs-url": _ctx.safeOfflineDocsUrl,
    "console-command": _ctx.consoleCommand,
    "licensing-available": _ctx.licensingOn,
    "self-slug": _ctx.selfSlug,
    "batch-nonce": _ctx.batchNonce,
    "batch-update-url": _ctx.batchUpdateUrl,
    "batch-finish-url": _ctx.batchFinishUrl,
    onOpen: _ctx.openDetail
  }, null, 8, ["items", "rows", "nonce", "update-url", "claim-url", "buy-url", "connect-url", "switch-now-url", "account-url", "offline-docs-url", "console-command", "licensing-available", "self-slug", "batch-nonce", "batch-update-url", "batch-finish-url", "onOpen"])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.tab === 'entdecken' && _ctx.promo ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_PromoBanner, {
    key: 4,
    promo: _ctx.promo,
    inline: "",
    onOpen: _ctx.openDetail
  }, null, 8, ["promo", "onOpen"])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.tab === 'entdecken' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_BrowseGrid, {
    key: 5,
    rows: _ctx.browseRows,
    onOpen: _ctx.openDetail
  }, null, 8, ["rows", "onOpen"])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.tab === 'lizenzen' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_LicenseTable, {
    key: 6,
    rows: _ctx.browseRows,
    nonce: _ctx.formNonce,
    "claim-url": _ctx.claimUrl,
    "buy-url": _ctx.buyUrl,
    "account-url": _ctx.accountUrl,
    "licensing-available": _ctx.licensingOn,
    onOpen: _ctx.openDetail
  }, null, 8, ["rows", "nonce", "claim-url", "buy-url", "account-url", "licensing-available", "onOpen"])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.tab === 'verwaltung' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_ManageTable, {
    key: 7,
    rows: _ctx.rows,
    nonce: _ctx.formNonce,
    "install-url": _ctx.installUrl,
    "update-url": _ctx.updateUrl,
    "activate-url": _ctx.activateUrl,
    "db-held": _ctx.dbHeld,
    "claim-url": _ctx.claimUrl,
    "buy-url": _ctx.buyUrl,
    "licensing-available": _ctx.licensingOn,
    "self-slug": _ctx.selfSlug,
    "batch-nonce": _ctx.batchNonce,
    "batch-update-url": _ctx.batchUpdateUrl,
    "batch-finish-url": _ctx.batchFinishUrl
  }, null, 8, ["rows", "nonce", "install-url", "update-url", "activate-url", "db-held", "claim-url", "buy-url", "licensing-available", "self-slug", "batch-nonce", "batch-update-url", "batch-finish-url"])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_FeedbackBar, {
    key: _ctx.feedbackTopic,
    topic: _ctx.feedbackTopic,
    question: _ctx.translate(`FeinwerkMarketplaceConnector_FeedbackQuestion_${_ctx.feedbackTopic}`),
    nonce: _ctx.formNonce,
    feedback: _ctx.feedback
  }, null, 8, ["topic", "question", "nonce", "feedback"]))], 64)), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_DetailModal, {
    slug: _ctx.detailSlug,
    row: _ctx.detailRow,
    nonce: _ctx.formNonce,
    "install-url": _ctx.installUrl,
    "update-url": _ctx.updateUrl,
    "activate-url": _ctx.activateUrl,
    "claim-url": _ctx.claimUrl,
    "buy-url": _ctx.buyUrl,
    "licensing-available": _ctx.licensingOn,
    onClose: _ctx.closeDetail
  }, null, 8, ["slug", "row", "nonce", "install-url", "update-url", "activate-url", "claim-url", "buy-url", "licensing-available", "onClose"])], 8, _hoisted_1);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/PluginsPage/PluginsPage.vue?vue&type=template&id=1f1a1b4b

// EXTERNAL MODULE: external "CoreHome"
var external_CoreHome_ = __webpack_require__("19dc");

// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/actionContext.ts
// Where the admin is on the Feinwerk Plugins screen. Every action form sends it right before it
// goes out (fwmc_tab, fwmc_return_open), so the server brings them back to the same tab and plugin
// window (Controller::backToScreen).
const TAB_MANAGE = 'verwaltung';
// The tab the screen shows (PluginsPage sets data-tab; one action per tab since 5.1.0).
function currentTab() {
  const page = document.querySelector('.fwmc[data-tab]');
  return page ? String(page.dataset.tab || '') : '';
}
function setField(form, name, value) {
  let input = form.querySelector(`input[name="${name}"]`);
  if (!input) {
    input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    form.appendChild(input);
  }
  input.value = value;
}
// returnOpen: the plugin whose window the form sits in, '' outside a window.
function addActionContext(form, returnOpen = '') {
  setField(form, 'fwmc_tab', currentTab());
  setField(form, 'fwmc_return_open', returnOpen);
}
// One action at a time on the whole screen: the forms share one nonce, so a second form sent before
// the page changes would end in Matomo's security-check error. Reset by leaving the page (a page
// restored from the browser's back cache reloads, PluginsPage).
let sent = false;
// Whether this page has sent an action (the page is about to change).
function submitted() {
  return sent;
}
function claimSubmit() {
  if (sent) {
    return false;
  }
  sent = true;
  return true;
}
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/ManageTable/ManageTable.vue?vue&type=template&id=6f9f5b1c

const ManageTablevue_type_template_id_6f9f5b1c_hoisted_1 = {
  class: "fwmc-manage"
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_2 = {
  class: "fwmc-tablewrap"
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_3 = {
  class: "fwmc-table entityTable"
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_4 = {
  class: "fwmc-select"
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_5 = {
  key: 0
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_6 = ["checked", "aria-label"];
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_7 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", null, null, -1);
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_8 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, null, -1);
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_9 = {
  class: "fwmc-select"
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_10 = {
  key: 0
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_11 = ["value", "aria-label"];
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_12 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", null, null, -1);
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_13 = {
  class: "fwmc-plugin-cell"
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_14 = {
  class: "fwmc-avatar",
  "aria-hidden": "true"
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_15 = {
  class: "fwmc-name"
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_16 = {
  key: 0,
  class: "fwmc-desc"
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_17 = ["href"];
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_18 = {
  key: 0,
  class: "fwmc-badge fwmc-badge--license-ok"
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_19 = {
  key: 1,
  class: "fwmc-badge fwmc-badge--license-missing"
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_20 = {
  key: 2
};
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_21 = ["title"];
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_22 = ["title"];
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_23 = ["href", "title"];
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_24 = ["title"];
const ManageTablevue_type_template_id_6f9f5b1c_hoisted_25 = {
  key: 5,
  class: "fwmc-meta"
};
function ManageTablevue_type_template_id_6f9f5b1c_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_BatchUpdate = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("BatchUpdate");
  const _component_InstallForm = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("InstallForm");
  const _component_LicenseActions = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("LicenseActions");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", ManageTablevue_type_template_id_6f9f5b1c_hoisted_1, [_ctx.batchCandidates.length && _ctx.batchNonce ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_BatchUpdate, {
    key: 0,
    candidates: _ctx.batchCandidates,
    selected: _ctx.selected,
    nonce: _ctx.batchNonce,
    "update-url": _ctx.batchUpdateUrl,
    "finish-url": _ctx.batchFinishUrl
  }, null, 8, ["candidates", "selected", "nonce", "update-url", "finish-url"])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", ManageTablevue_type_template_id_6f9f5b1c_hoisted_2, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("table", ManageTablevue_type_template_id_6f9f5b1c_hoisted_3, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("thead", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("tr", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", ManageTablevue_type_template_id_6f9f5b1c_hoisted_4, [_ctx.batchCandidates.length > 1 ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("label", ManageTablevue_type_template_id_6f9f5b1c_hoisted_5, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "checkbox",
    checked: _ctx.allSelected,
    "aria-label": _ctx.translate('FeinwerkMarketplaceConnector_BatchSelectAll'),
    onChange: _cache[0] || (_cache[0] = (...args) => _ctx.toggleAll && _ctx.toggleAll(...args))
  }, null, 40, ManageTablevue_type_template_id_6f9f5b1c_hoisted_6), ManageTablevue_type_template_id_6f9f5b1c_hoisted_7])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ColumnPlugin')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ColumnTier')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ColumnLicense')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ColumnInstalled')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ColumnLatest')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ColumnStatus')), 1), ManageTablevue_type_template_id_6f9f5b1c_hoisted_8])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("tbody", null, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.rows, row => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("tr", {
      key: row.slug
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", ManageTablevue_type_template_id_6f9f5b1c_hoisted_9, [_ctx.canBatch(row) ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("label", ManageTablevue_type_template_id_6f9f5b1c_hoisted_10, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
      "onUpdate:modelValue": _cache[1] || (_cache[1] = $event => _ctx.selected = $event),
      type: "checkbox",
      value: row.slug,
      "aria-label": _ctx.translate('FeinwerkMarketplaceConnector_BatchSelect', row.displayName)
    }, null, 8, ManageTablevue_type_template_id_6f9f5b1c_hoisted_11), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelCheckbox"], _ctx.selected]]), ManageTablevue_type_template_id_6f9f5b1c_hoisted_12])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", ManageTablevue_type_template_id_6f9f5b1c_hoisted_13, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", ManageTablevue_type_template_id_6f9f5b1c_hoisted_14, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.monogram(row.displayName)), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", ManageTablevue_type_template_id_6f9f5b1c_hoisted_15, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(row.displayName), 1), row.description ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", ManageTablevue_type_template_id_6f9f5b1c_hoisted_16, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(row.description), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), row.url ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
      key: 1,
      href: row.url,
      target: "_blank",
      rel: "noreferrer noopener",
      class: "fwmc-learn"
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_LearnMore')) + " ↗ ", 9, ManageTablevue_type_template_id_6f9f5b1c_hoisted_17)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", {
      class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])(["fwmc-badge", 'fwmc-badge--' + row.tier])
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(row.tier), 3)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, [row.license ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", ManageTablevue_type_template_id_6f9f5b1c_hoisted_18, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_LicenseActive')) + " ", 1), row.license.valid_until ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 0
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" · " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_LicenseUntil', row.license.valid_until)), 1)], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])) : row.needsLicense ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", ManageTablevue_type_template_id_6f9f5b1c_hoisted_19, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_NoLicense')), 1)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", ManageTablevue_type_template_id_6f9f5b1c_hoisted_20, "-"))]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(row.installedVersion || '-'), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(row.latestVersion || '-'), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, [row.switching ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", {
      key: 0,
      class: "fwmc-badge fwmc-status--not_installed",
      title: _ctx.translate('FeinwerkMarketplaceConnector_SwitchingHint')
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_Switching')), 9, ManageTablevue_type_template_id_6f9f5b1c_hoisted_21)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", {
      key: 1,
      class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])(["fwmc-badge", 'fwmc-status--' + row.status])
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.statusLabel(row.status)), 3)), row.inactive ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", {
      key: 2,
      class: "fwmc-badge fwmc-status--inactive",
      title: _ctx.translate('FeinwerkMarketplaceConnector_StatusInactiveHint')
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_StatusInactive')), 9, ManageTablevue_type_template_id_6f9f5b1c_hoisted_22)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, [_ctx.actionFor(row) ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_InstallForm, {
      key: 0,
      "action-url": _ctx.actionUrl(row),
      nonce: _ctx.nonce,
      slug: row.slug,
      version: row.latestVersion,
      label: _ctx.actionFor(row),
      "plugin-name": row.displayName,
      "is-update": row.status === 'update_available',
      "db-change": row.dbChange || null,
      held: _ctx.dbHeld.includes(row.slug) && row.status === 'update_available'
    }, null, 8, ["action-url", "nonce", "slug", "version", "label", "plugin-name", "is-update", "db-change", "held"])) : _ctx.licensingAvailable && row.licenseClaimable ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_LicenseActions, {
      key: 1,
      kind: "claim",
      url: _ctx.claimUrl,
      nonce: _ctx.nonce,
      slug: row.slug
    }, null, 8, ["url", "nonce", "slug"])) : _ctx.canBuy(row) ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_LicenseActions, {
      key: 2,
      kind: "buy",
      url: _ctx.buyUrl,
      nonce: _ctx.nonce,
      slug: row.slug
    }, null, 8, ["url", "nonce", "slug"])) : row.inactive && _ctx.activateUrl ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
      key: 3,
      class: "btn",
      href: _ctx.activateLink(_ctx.activateUrl, row.slug),
      title: _ctx.translate('FeinwerkMarketplaceConnector_StatusInactiveHint')
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_Activate')), 9, ManageTablevue_type_template_id_6f9f5b1c_hoisted_23)) : row.devCheckout && row.status === 'update_available' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", {
      key: 4,
      class: "fwmc-badge fwmc-status--not_installed",
      title: _ctx.translate('FeinwerkMarketplaceConnector_DevCheckoutHint')
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DevCheckout')), 9, ManageTablevue_type_template_id_6f9f5b1c_hoisted_24)) : !row.latestVersion ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", ManageTablevue_type_template_id_6f9f5b1c_hoisted_25, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_NoVersionYet')), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])]);
  }), 128))])])])]);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/ManageTable/ManageTable.vue?vue&type=template&id=6f9f5b1c

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/InstallForm/InstallForm.vue?vue&type=template&id=3c56372d

const InstallFormvue_type_template_id_3c56372d_hoisted_1 = ["action"];
const InstallFormvue_type_template_id_3c56372d_hoisted_2 = ["value"];
const InstallFormvue_type_template_id_3c56372d_hoisted_3 = ["value"];
const InstallFormvue_type_template_id_3c56372d_hoisted_4 = ["value"];
const InstallFormvue_type_template_id_3c56372d_hoisted_5 = {
  key: 0,
  type: "hidden",
  name: "activate",
  value: "0"
};
const InstallFormvue_type_template_id_3c56372d_hoisted_6 = ["disabled", "aria-busy"];
const InstallFormvue_type_template_id_3c56372d_hoisted_7 = ["title"];
const InstallFormvue_type_template_id_3c56372d_hoisted_8 = ["aria-label"];
const InstallFormvue_type_template_id_3c56372d_hoisted_9 = {
  class: "fwmc-dbdialog-box"
};
const InstallFormvue_type_template_id_3c56372d_hoisted_10 = {
  class: "fwmc-dbdialog-head"
};
const InstallFormvue_type_template_id_3c56372d_hoisted_11 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", {
  "aria-hidden": "true"
}, "⚠", -1);
const InstallFormvue_type_template_id_3c56372d_hoisted_12 = {
  class: "fwmc-dbdialog-body"
};
const InstallFormvue_type_template_id_3c56372d_hoisted_13 = {
  class: "fwmc-dbdialog-plugin"
};
const InstallFormvue_type_template_id_3c56372d_hoisted_14 = {
  key: 0
};
const InstallFormvue_type_template_id_3c56372d_hoisted_15 = {
  key: 1
};
const InstallFormvue_type_template_id_3c56372d_hoisted_16 = {
  key: 2
};
const InstallFormvue_type_template_id_3c56372d_hoisted_17 = {
  class: "fwmc-dbdialog-duration"
};
const InstallFormvue_type_template_id_3c56372d_hoisted_18 = {
  key: 0,
  class: "fwmc-dbdialog-large"
};
const InstallFormvue_type_template_id_3c56372d_hoisted_19 = {
  key: 0
};
const InstallFormvue_type_template_id_3c56372d_hoisted_20 = {
  key: 1
};
const InstallFormvue_type_template_id_3c56372d_hoisted_21 = {
  class: "fwmc-dbdialog-ack"
};
const InstallFormvue_type_template_id_3c56372d_hoisted_22 = {
  key: 1,
  class: "fwmc-dbdialog-option"
};
const InstallFormvue_type_template_id_3c56372d_hoisted_23 = {
  class: "fwmc-dbdialog-ack"
};
const InstallFormvue_type_template_id_3c56372d_hoisted_24 = {
  key: 0
};
const InstallFormvue_type_template_id_3c56372d_hoisted_25 = {
  key: 2,
  class: "fwmc-meta"
};
const InstallFormvue_type_template_id_3c56372d_hoisted_26 = {
  class: "fwmc-dbdialog-actions"
};
const InstallFormvue_type_template_id_3c56372d_hoisted_27 = ["disabled"];
function InstallFormvue_type_template_id_3c56372d_render(_ctx, _cache, $props, $setup, $data, $options) {
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("form", {
    method: "post",
    action: _ctx.actionUrl,
    class: "fwmc-install",
    ref: "form",
    onSubmit: _cache[6] || (_cache[6] = (...args) => _ctx.onSubmit && _ctx.onSubmit(...args))
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "nonce",
    value: _ctx.nonce
  }, null, 8, InstallFormvue_type_template_id_3c56372d_hoisted_2), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "slug",
    value: _ctx.slug
  }, null, 8, InstallFormvue_type_template_id_3c56372d_hoisted_3), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "version",
    value: _ctx.version
  }, null, 8, InstallFormvue_type_template_id_3c56372d_hoisted_4), _ctx.activateOnConsole ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("input", InstallFormvue_type_template_id_3c56372d_hoisted_5)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "submit",
    class: "btn",
    disabled: _ctx.busy,
    "aria-busy": _ctx.busy,
    onClick: _cache[0] || (_cache[0] = (...args) => _ctx.onClick && _ctx.onClick(...args))
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.busy ? _ctx.translate('FeinwerkMarketplaceConnector_Working') : _ctx.label), 9, InstallFormvue_type_template_id_3c56372d_hoisted_6), _ctx.dbChange || _ctx.held ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", {
    key: 1,
    class: "fwmc-badge fwmc-badge--dbwarn",
    title: _ctx.badgeHint
  }, " ⚠ " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DbHeld')), 9, InstallFormvue_type_template_id_3c56372d_hoisted_7)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Teleport"], {
    to: "body"
  }, [_ctx.confirming ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", {
    key: 0,
    class: "fwmc fwmc-dbdialog",
    role: "alertdialog",
    "aria-modal": "true",
    "aria-label": _ctx.title,
    onClick: _cache[5] || (_cache[5] = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])($event => _ctx.confirming = false, ["self"]))
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", InstallFormvue_type_template_id_3c56372d_hoisted_9, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", InstallFormvue_type_template_id_3c56372d_hoisted_10, [InstallFormvue_type_template_id_3c56372d_hoisted_11, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.title), 1)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", InstallFormvue_type_template_id_3c56372d_hoisted_12, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", InstallFormvue_type_template_id_3c56372d_hoisted_13, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.pluginName || _ctx.slug) + " " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.version), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("ul", null, [_ctx.dbChange.migrations.length ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("li", InstallFormvue_type_template_id_3c56372d_hoisted_14, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.migrationsText), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.dbChange.columns.length ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("li", InstallFormvue_type_template_id_3c56372d_hoisted_15, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.columnsText), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.dbChange.note ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("li", InstallFormvue_type_template_id_3c56372d_hoisted_16, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.dbChange.note), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", InstallFormvue_type_template_id_3c56372d_hoisted_17, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DbChangeDuration')), 1), _ctx.large ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", InstallFormvue_type_template_id_3c56372d_hoisted_18, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("strong", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.largeIntro), 1)]), _ctx.activateOnConsole ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("ol", InstallFormvue_type_template_id_3c56372d_hoisted_19, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("li", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DbChangeStepInstallOnly')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("li", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DbChangeStepActivate')) + " ", 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("code", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.consoleCmd) + " plugin:activate " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.slug), 1)])])) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("ol", InstallFormvue_type_template_id_3c56372d_hoisted_20, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("li", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.stepClick), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("li", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DbChangeStepConsole')) + " ", 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("code", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.consoleCmd) + " core:update", 1)])])), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("label", InstallFormvue_type_template_id_3c56372d_hoisted_21, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "checkbox",
    "onUpdate:modelValue": _cache[1] || (_cache[1] = $event => _ctx.acknowledged = $event)
  }, null, 512), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelCheckbox"], _ctx.acknowledged]]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.ackLabel), 1)])])) : _ctx.addsColumns ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", InstallFormvue_type_template_id_3c56372d_hoisted_22, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("label", InstallFormvue_type_template_id_3c56372d_hoisted_23, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "checkbox",
    "onUpdate:modelValue": _cache[2] || (_cache[2] = $event => _ctx.consoleActivate = $event)
  }, null, 512), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelCheckbox"], _ctx.consoleActivate]]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DbChangeOptConsoleActivate')), 1)]), _ctx.consoleActivate ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("code", InstallFormvue_type_template_id_3c56372d_hoisted_24, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.consoleCmd) + " plugin:activate " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.slug), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", InstallFormvue_type_template_id_3c56372d_hoisted_25, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DbChangeCliHint')) + " ", 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("code", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.consoleCmd) + " core:update", 1)]))]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", InstallFormvue_type_template_id_3c56372d_hoisted_26, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "button",
    class: "btn",
    disabled: _ctx.large && !_ctx.acknowledged,
    onClick: _cache[3] || (_cache[3] = (...args) => _ctx.submit && _ctx.submit(...args))
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.confirmLabel), 9, InstallFormvue_type_template_id_3c56372d_hoisted_27), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "button",
    class: "btn btn-flat",
    onClick: _cache[4] || (_cache[4] = $event => _ctx.confirming = false)
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DbChangeCancel')), 1)])])], 8, InstallFormvue_type_template_id_3c56372d_hoisted_8)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]))], 40, InstallFormvue_type_template_id_3c56372d_hoisted_1);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/InstallForm/InstallForm.vue?vue&type=template&id=3c56372d

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/InstallForm/InstallForm.vue?vue&type=script&lang=ts



// Install/Update button. When the install changes the database (row.dbChange from the
// marketplace's db_changes, see Core/DbChangePlan), the click opens a dialog: what changes, that
// it can take long, and on a large instance (InstanceSizeProbe) the console way, confirmed with a
// checkbox. Only the dialog's button installs.
/* harmony default export */ var InstallFormvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  props: {
    actionUrl: {
      type: String,
      required: true
    },
    nonce: {
      type: String,
      required: true
    },
    slug: {
      type: String,
      required: true
    },
    version: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    pluginName: {
      type: String,
      default: ''
    },
    isUpdate: {
      type: Boolean,
      default: false
    },
    dbChange: {
      type: Object,
      default: null
    },
    // Held back by the automatic updates for database changes (older marketplace answers
    // without db_changes).
    held: {
      type: Boolean,
      default: false
    }
  },
  inject: {
    instance: {
      from: 'fwmcInstance',
      default: null
    },
    // Inside a plugin window: the server reopens it after the action.
    inWindow: {
      from: 'fwmcInWindow',
      default: false
    }
  },
  data() {
    // busy: sent once, the button stays off so a double click cannot spend the nonce twice.
    return {
      confirming: false,
      acknowledged: false,
      consoleActivate: false,
      busy: false
    };
  },
  computed: {
    info() {
      return this.instance;
    },
    // The console way only matters when Matomo really runs something: update files or new
    // log columns (a plan with only the author's note needs no core:update).
    large() {
      var _this$info;
      const change = this.dbChange;
      return !!((_this$info = this.info) !== null && _this$info !== void 0 && _this$info.large) && !!change && (change.migrations.length > 0 || change.columns.length > 0);
    },
    // A fresh install whose dimensions add log table columns: on a large instance the
    // ALTER TABLE runs on the console.
    addsColumns() {
      return !this.isUpdate && !!this.dbChange && this.dbChange.columns.length > 0;
    },
    activateOnConsole() {
      return this.addsColumns && (this.large || this.consoleActivate);
    },
    consoleCmd() {
      var _this$info2;
      return ((_this$info2 = this.info) === null || _this$info2 === void 0 ? void 0 : _this$info2.console) || './console';
    },
    badgeHint() {
      return Object(external_CoreHome_["translate"])(this.dbChange ? 'FeinwerkMarketplaceConnector_DbChangeBadgeHint' : 'FeinwerkMarketplaceConnector_DbHeldHint');
    },
    migrationsText() {
      var _this$dbChange;
      const versions = (((_this$dbChange = this.dbChange) === null || _this$dbChange === void 0 ? void 0 : _this$dbChange.migrations) || []).join(', ');
      return Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_DbChangeMigrations', versions);
    },
    columnsText() {
      var _this$dbChange2;
      const columns = (((_this$dbChange2 = this.dbChange) === null || _this$dbChange2 === void 0 ? void 0 : _this$dbChange2.columns) || []).join(', ');
      return Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_DbChangeColumns', columns);
    },
    stepClick() {
      return Object(external_CoreHome_["translate"])(this.isUpdate ? 'FeinwerkMarketplaceConnector_DbChangeStepClickUpdate' : 'FeinwerkMarketplaceConnector_DbChangeStepClickInstall');
    },
    ackLabel() {
      return Object(external_CoreHome_["translate"])(this.activateOnConsole ? 'FeinwerkMarketplaceConnector_DbChangeAckActivate' : 'FeinwerkMarketplaceConnector_DbChangeAckConsole');
    },
    confirmLabel() {
      return Object(external_CoreHome_["translate"])(this.isUpdate ? 'FeinwerkMarketplaceConnector_DbChangeConfirmUpdate' : 'FeinwerkMarketplaceConnector_DbChangeConfirmInstall');
    },
    // Update files alone may only re-archive reports (no table change): then "needs a database
    // update", with new log columns "changes the database".
    title() {
      if (this.isUpdate && this.dbChange && !this.dbChange.columns.length && this.dbChange.migrations.length) {
        return Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_DbChangeTitleMigration');
      }
      return Object(external_CoreHome_["translate"])(this.isUpdate ? 'FeinwerkMarketplaceConnector_DbChangeTitleUpdate' : 'FeinwerkMarketplaceConnector_DbChangeTitleInstall');
    },
    largeIntro() {
      var _this$info3, _this$info4;
      const visits = (_this$info3 = this.info) === null || _this$info3 === void 0 ? void 0 : _this$info3.visits;
      if (!visits) {
        return Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_DbChangeLargeUnknown');
      }
      // Formatted by Matomo in the admin's language (Controller::instanceInfo).
      const count = ((_this$info4 = this.info) === null || _this$info4 === void 0 ? void 0 : _this$info4.visitsLabel) || String(visits);
      return Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_DbChangeLarge', count);
    }
  },
  methods: {
    translate: external_CoreHome_["translate"],
    onClick(event) {
      if (this.busy) {
        event.preventDefault();
        return;
      }
      if (this.dbChange) {
        event.preventDefault();
        this.acknowledged = false;
        this.consoleActivate = false;
        this.confirming = true;
      }
    },
    onSubmit(event) {
      if (this.busy || !claimSubmit()) {
        event.preventDefault();
        return;
      }
      this.prepare();
    },
    prepare() {
      const {
        inWindow
      } = this;
      addActionContext(this.$refs.form, inWindow ? this.slug : '');
      this.busy = true;
    },
    submit() {
      if (this.busy || this.large && !this.acknowledged || !claimSubmit()) {
        return;
      }
      this.confirming = false;
      this.prepare();
      // form.submit() fires no submit event, so onSubmit() does not run twice.
      this.$refs.form.submit();
    }
  },
  beforeUnmount() {
    const {
      onKey
    } = this;
    if (onKey) {
      document.removeEventListener('keydown', onKey, true);
    }
  },
  watch: {
    confirming(open) {
      const onKey = this.onKey || (e => {
        if (e.key === 'Escape') {
          e.stopPropagation();
          this.confirming = false;
        }
      });
      this.onKey = onKey;
      if (open) {
        document.addEventListener('keydown', onKey, true);
      } else {
        document.removeEventListener('keydown', onKey, true);
      }
    }
  }
}));
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/InstallForm/InstallForm.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/InstallForm/InstallForm.vue



InstallFormvue_type_script_lang_ts.render = InstallFormvue_type_template_id_3c56372d_render

/* harmony default export */ var InstallForm = (InstallFormvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/LicenseActions/LicenseActions.vue?vue&type=template&id=9898e686

const LicenseActionsvue_type_template_id_9898e686_hoisted_1 = ["action"];
const LicenseActionsvue_type_template_id_9898e686_hoisted_2 = ["value"];
const LicenseActionsvue_type_template_id_9898e686_hoisted_3 = ["value"];
const LicenseActionsvue_type_template_id_9898e686_hoisted_4 = ["title", "disabled", "aria-busy"];
function LicenseActionsvue_type_template_id_9898e686_render(_ctx, _cache, $props, $setup, $data, $options) {
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("form", {
    method: "post",
    action: _ctx.url,
    class: "fwmc-license-action",
    onSubmit: _cache[0] || (_cache[0] = (...args) => _ctx.onSubmit && _ctx.onSubmit(...args))
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "nonce",
    value: _ctx.nonce
  }, null, 8, LicenseActionsvue_type_template_id_9898e686_hoisted_2), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "slug",
    value: _ctx.slug
  }, null, 8, LicenseActionsvue_type_template_id_9898e686_hoisted_3), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "submit",
    class: "btn",
    title: _ctx.hint,
    disabled: _ctx.busy,
    "aria-busy": _ctx.busy
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.busy ? _ctx.translate('FeinwerkMarketplaceConnector_Working') : _ctx.label), 9, LicenseActionsvue_type_template_id_9898e686_hoisted_4)], 40, LicenseActionsvue_type_template_id_9898e686_hoisted_1);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/LicenseActions/LicenseActions.vue?vue&type=template&id=9898e686

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/LicenseActions/LicenseActions.vue?vue&type=script&lang=ts



// "Use an existing license" (kind claim) and "Buy a license" (kind buy): one POST form to the
// connector's claimLicense or buy action. The server decides; the nonce is checked there.
/* harmony default export */ var LicenseActionsvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  props: {
    kind: {
      type: String,
      required: true
    },
    url: {
      type: String,
      required: true
    },
    nonce: {
      type: String,
      required: true
    },
    slug: {
      type: String,
      required: true
    }
  },
  inject: {
    inWindow: {
      from: 'fwmcInWindow',
      default: false
    }
  },
  data() {
    return {
      busy: false
    };
  },
  methods: {
    translate: external_CoreHome_["translate"],
    // Sent once: a double click cannot spend the nonce twice. The server returns to this tab
    // and window.
    onSubmit(event) {
      if (this.busy || !claimSubmit()) {
        event.preventDefault();
        return;
      }
      const {
        inWindow
      } = this;
      addActionContext(event.target, inWindow ? this.slug : '');
      this.busy = true;
    }
  },
  computed: {
    label() {
      return Object(external_CoreHome_["translate"])(this.kind === 'claim' ? 'FeinwerkMarketplaceConnector_ClaimLicense' : 'FeinwerkMarketplaceConnector_BuyLicense');
    },
    hint() {
      return Object(external_CoreHome_["translate"])(this.kind === 'claim' ? 'FeinwerkMarketplaceConnector_ClaimLicenseHint' : 'FeinwerkMarketplaceConnector_BuyLicenseHint');
    }
  }
}));
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/LicenseActions/LicenseActions.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/LicenseActions/LicenseActions.vue



LicenseActionsvue_type_script_lang_ts.render = LicenseActionsvue_type_template_id_9898e686_render

/* harmony default export */ var LicenseActions = (LicenseActionsvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/BatchUpdate/BatchUpdate.vue?vue&type=template&id=74a73a48

const BatchUpdatevue_type_template_id_74a73a48_hoisted_1 = {
  class: "fwmc-batch"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_2 = {
  key: 0,
  class: "fwmc-batch-buttons"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_3 = {
  key: 1,
  class: "fwmc-batch-progress",
  "aria-live": "polite"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_4 = {
  class: "fwmc-batch-head"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_5 = {
  key: 0,
  class: "fwmc-batch-message"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_6 = {
  class: "fwmc-batch-name"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_7 = {
  class: "fwmc-batch-state"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_8 = {
  key: 0,
  class: "fwmc-batch-message"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_9 = ["action"];
const BatchUpdatevue_type_template_id_74a73a48_hoisted_10 = ["value"];
const BatchUpdatevue_type_template_id_74a73a48_hoisted_11 = ["value"];
const BatchUpdatevue_type_template_id_74a73a48_hoisted_12 = ["value"];
const BatchUpdatevue_type_template_id_74a73a48_hoisted_13 = ["value"];
const BatchUpdatevue_type_template_id_74a73a48_hoisted_14 = ["aria-label"];
const BatchUpdatevue_type_template_id_74a73a48_hoisted_15 = {
  class: "fwmc-dbdialog-box"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_16 = {
  class: "fwmc-dbdialog-head"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_17 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", {
  "aria-hidden": "true"
}, "⚠", -1);
const BatchUpdatevue_type_template_id_74a73a48_hoisted_18 = {
  class: "fwmc-dbdialog-body"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_19 = {
  class: "fwmc-dbdialog-duration"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_20 = {
  key: 0,
  class: "fwmc-dbdialog-large"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_21 = {
  class: "fwmc-dbdialog-ack"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_22 = {
  class: "fwmc-dbdialog-actions"
};
const BatchUpdatevue_type_template_id_74a73a48_hoisted_23 = ["disabled"];
function BatchUpdatevue_type_template_id_74a73a48_render(_ctx, _cache, $props, $setup, $data, $options) {
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", BatchUpdatevue_type_template_id_74a73a48_hoisted_1, [!_ctx.running ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", BatchUpdatevue_type_template_id_74a73a48_hoisted_2, [_ctx.selected.length ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("button", {
    key: 0,
    type: "button",
    class: "btn",
    onClick: _cache[0] || (_cache[0] = $event => _ctx.start(_ctx.selectedRows))
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.label('BatchUpdateSelected', _ctx.selected.length)), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.candidates.length > 1 ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("button", {
    key: 1,
    type: "button",
    class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])(_ctx.selected.length ? 'btn btn-flat' : 'btn'),
    onClick: _cache[1] || (_cache[1] = $event => _ctx.start(_ctx.candidates))
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.label('BatchUpdateAll', _ctx.candidates.length)), 3)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", BatchUpdatevue_type_template_id_74a73a48_hoisted_3, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", BatchUpdatevue_type_template_id_74a73a48_hoisted_4, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.progressText), 1), _ctx.expired ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", BatchUpdatevue_type_template_id_74a73a48_hoisted_5, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.expired) + " ", 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("a", {
    href: "",
    class: "btn btn-flat",
    onClick: _cache[2] || (_cache[2] = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])((...args) => _ctx.reload && _ctx.reload(...args), ["prevent"]))
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_BatchReload')), 1)])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("ul", null, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.steps, step => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("li", {
      key: step.slug,
      class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])('fwmc-batch-' + step.state)
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", BatchUpdatevue_type_template_id_74a73a48_hoisted_6, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(step.name) + " " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(step.version), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", BatchUpdatevue_type_template_id_74a73a48_hoisted_7, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.stateLabel(step.state)), 1), step.state === 'fail' && step.message ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", BatchUpdatevue_type_template_id_74a73a48_hoisted_8, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(step.message), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)], 2);
  }), 128))])])), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("form", {
    ref: "finish",
    method: "post",
    action: _ctx.finishUrl,
    class: "fwmc-batch-finish"
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "nonce",
    value: _ctx.nonce
  }, null, 8, BatchUpdatevue_type_template_id_74a73a48_hoisted_10), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "held",
    value: _ctx.slugsIn('held')
  }, null, 8, BatchUpdatevue_type_template_id_74a73a48_hoisted_11), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "no_answer",
    value: _ctx.slugsIn('noanswer')
  }, null, 8, BatchUpdatevue_type_template_id_74a73a48_hoisted_12), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "hidden",
    name: "not_started",
    value: _ctx.slugsIn('notstarted')
  }, null, 8, BatchUpdatevue_type_template_id_74a73a48_hoisted_13)], 8, BatchUpdatevue_type_template_id_74a73a48_hoisted_9), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Teleport"], {
    to: "body"
  }, [_ctx.confirming ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", {
    key: 0,
    class: "fwmc fwmc-dbdialog",
    role: "alertdialog",
    "aria-modal": "true",
    "aria-label": _ctx.translate('FeinwerkMarketplaceConnector_BatchDbTitle'),
    onClick: _cache[6] || (_cache[6] = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])($event => _ctx.confirming = false, ["self"]))
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", BatchUpdatevue_type_template_id_74a73a48_hoisted_15, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", BatchUpdatevue_type_template_id_74a73a48_hoisted_16, [BatchUpdatevue_type_template_id_74a73a48_hoisted_17, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_BatchDbTitle')), 1)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", BatchUpdatevue_type_template_id_74a73a48_hoisted_18, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_BatchDbIntro')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("ul", null, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.dbRows, row => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("li", {
      key: row.slug
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(row.displayName) + " " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(row.latestVersion), 1);
  }), 128))]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", BatchUpdatevue_type_template_id_74a73a48_hoisted_19, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DbChangeDuration')), 1), _ctx.large ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", BatchUpdatevue_type_template_id_74a73a48_hoisted_20, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("strong", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.largeIntro), 1)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("ol", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("li", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_BatchDbStepClick')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("li", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DbChangeStepConsole')) + " ", 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("code", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.consoleCmd) + " core:update", 1)])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("label", BatchUpdatevue_type_template_id_74a73a48_hoisted_21, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "checkbox",
    "onUpdate:modelValue": _cache[3] || (_cache[3] = $event => _ctx.acknowledged = $event)
  }, null, 512), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelCheckbox"], _ctx.acknowledged]]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DbChangeAckConsole')), 1)])])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", BatchUpdatevue_type_template_id_74a73a48_hoisted_22, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "button",
    class: "btn",
    disabled: _ctx.large && !_ctx.acknowledged,
    onClick: _cache[4] || (_cache[4] = (...args) => _ctx.confirm && _ctx.confirm(...args))
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_BatchDbConfirm')), 9, BatchUpdatevue_type_template_id_74a73a48_hoisted_23), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "button",
    class: "btn btn-flat",
    onClick: _cache[5] || (_cache[5] = $event => _ctx.confirming = false)
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DbChangeCancel')), 1)])])], 8, BatchUpdatevue_type_template_id_74a73a48_hoisted_14)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]))]);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/BatchUpdate/BatchUpdate.vue?vue&type=template&id=74a73a48

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/BatchUpdate/BatchUpdate.vue?vue&type=script&lang=ts



function changesDatabase(row) {
  const change = row.dbChange;
  return !!change && ((change.migrations || []).length > 0 || (change.columns || []).length > 0);
}
/* harmony default export */ var BatchUpdatevue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  props: {
    // Every row that can be updated this way (Manage and Aufgaben tabs).
    candidates: {
      type: Array,
      required: true
    },
    // The slugs the admin ticked on the Manage tab.
    selected: {
      type: Array,
      default: () => []
    },
    nonce: {
      type: String,
      required: true
    },
    updateUrl: {
      type: String,
      required: true
    },
    finishUrl: {
      type: String,
      required: true
    }
  },
  inject: {
    instance: {
      from: 'fwmcInstance',
      default: null
    }
  },
  data() {
    return {
      running: false,
      confirming: false,
      acknowledged: false,
      pending: [],
      steps: [],
      // The batch nonce expired (an hour old page, or finished in another tab): stop and ask
      // to reload.
      expired: ''
    };
  },
  computed: {
    info() {
      return this.instance;
    },
    selectedRows() {
      return this.candidates.filter(r => this.selected.includes(r.slug));
    },
    dbRows() {
      return this.pending.filter(changesDatabase);
    },
    large() {
      var _this$info;
      return !!((_this$info = this.info) !== null && _this$info !== void 0 && _this$info.large) && this.dbRows.length > 0;
    },
    largeIntro() {
      var _this$info2, _this$info3;
      const visits = (_this$info2 = this.info) === null || _this$info2 === void 0 ? void 0 : _this$info2.visits;
      if (!visits) {
        return Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_DbChangeLargeUnknown');
      }
      return Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_DbChangeLarge', ((_this$info3 = this.info) === null || _this$info3 === void 0 ? void 0 : _this$info3.visitsLabel) || String(visits));
    },
    consoleCmd() {
      var _this$info4;
      return ((_this$info4 = this.info) === null || _this$info4 === void 0 ? void 0 : _this$info4.console) || './console';
    },
    progressText() {
      const done = this.steps.filter(s => s.state !== 'waiting' && s.state !== 'running').length;
      if (done === this.steps.length) {
        return Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_BatchFinishing');
      }
      return Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_BatchProgress', String(done), String(this.steps.length));
    }
  },
  methods: {
    translate: external_CoreHome_["translate"],
    label(key, count) {
      return Object(external_CoreHome_["translate"])(`FeinwerkMarketplaceConnector_${key}`, String(count));
    },
    stateLabel(state) {
      const keys = {
        waiting: 'BatchWaiting',
        running: 'BatchRunning',
        ok: 'BatchOk',
        fail: 'BatchFail',
        held: 'BatchHeldState',
        noanswer: 'BatchNoAnswerState',
        notstarted: 'BatchNotStartedState'
      };
      return Object(external_CoreHome_["translate"])(`FeinwerkMarketplaceConnector_${keys[state]}`);
    },
    slugsIn(state) {
      return this.steps.filter(s => s.state === state).map(s => s.slug).join(',');
    },
    reload() {
      window.location.reload();
    },
    start(rows) {
      if (!rows.length || this.running) {
        return;
      }
      this.pending = rows;
      if (this.dbRows.length) {
        this.acknowledged = false;
        this.confirming = true;
        return;
      }
      this.run();
    },
    confirm() {
      if (this.large && !this.acknowledged) {
        return;
      }
      this.confirming = false;
      this.run();
    },
    async run() {
      // The page's other forms share a nonce: nothing else may be sent while this runs.
      if (!claimSubmit()) {
        return;
      }
      this.running = true;
      // Updates without a database change first: once one leaves a database update pending, Matomo
      // sends every further request to its update page, and the rest waits for it.
      const ordered = [...this.pending.filter(r => !changesDatabase(r)), ...this.dbRows];
      this.steps = ordered.map(r => ({
        slug: r.slug,
        name: r.displayName,
        version: r.latestVersion || '',
        state: 'waiting',
        message: ''
      }));
      for (let i = 0; i < this.steps.length; i += 1) {
        const step = this.steps[i];
        const restSlugs = this.steps.slice(i + 1).map(s => s.slug).join(',');
        step.state = 'running';
        let stop = false;
        let rest = 'held';
        try {
          // One request per plugin, in order: never several installs at once.
          // eslint-disable-next-line no-await-in-loop
          const response = await fetch(this.updateUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              Accept: 'application/json'
            },
            body: new URLSearchParams({
              nonce: this.nonce,
              slug: step.slug,
              version: step.version,
              first: i === 0 ? '1' : '0',
              rest: restSlugs
            })
          });
          const json = (response.headers.get('Content-Type') || '').includes('json');
          if (response.redirected && /module=CoreUpdater|action=consoleUpdate/.test(response.url)) {
            // Matomo's update page answered instead: nothing more can be installed before it ran.
            step.state = 'held';
            stop = true;
          } else if (!json) {
            // No answer of ours (Bad Gateway, an error page, the login page): the update may
            // still run on the server, so nothing more is started next to it.
            step.state = 'noanswer';
            step.message = `HTTP ${response.status}`;
            rest = 'notstarted';
            stop = true;
          } else {
            // eslint-disable-next-line no-await-in-loop
            const result = await response.json();
            if (result && result.expired) {
              step.state = 'notstarted';
              this.steps.slice(i + 1).forEach(s => {
                s.state = 'notstarted';
              });
              this.expired = String(result.message || '');
              return;
            }
            step.state = result && result.ok ? 'ok' : 'fail';
            step.message = result && !result.ok ? String(result.message || '') : '';
            stop = !!(result && result.stop);
          }
        } catch (e) {
          // The connection broke: as with no answer, nothing more is started.
          step.state = 'noanswer';
          step.message = String(e);
          rest = 'notstarted';
          stop = true;
        }
        if (stop) {
          this.steps.slice(i + 1).forEach(s => {
            s.state = rest;
          });
          break;
        }
      }
      // The hidden fields read the step states: let Vue write them before the form goes.
      await this.$nextTick();
      const form = this.$refs.finish;
      addActionContext(form);
      form.submit();
    }
  }
}));
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/BatchUpdate/BatchUpdate.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/BatchUpdate/BatchUpdate.vue



BatchUpdatevue_type_script_lang_ts.render = BatchUpdatevue_type_template_id_74a73a48_render

/* harmony default export */ var BatchUpdate = (BatchUpdatevue_type_script_lang_ts);
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/batch.ts
// Which rows "update several at once" may take (BatchUpdate): an update the admin could also
// install with a click, never the connector itself (its update replaces the code that runs the
// batch), a development checkout, a plugin a Matomo switch is still handling or one without a
// license.
function canBatch(row, selfSlug) {
  return row.status === 'update_available' && !!row.latestVersion && row.slug !== selfSlug && !row.devCheckout && !row.switching && !(row.needsLicense && !row.license);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/activate.ts
/** Matomo's own activate link (Controller::activateUrl) for one plugin; '' when not allowed. */
function activateLink(base, slug) {
  return base ? `${base}&pluginName=${encodeURIComponent(slug)}` : '';
}
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/ManageTable/ManageTable.vue?vue&type=script&lang=ts







/* harmony default export */ var ManageTablevue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  components: {
    InstallForm: InstallForm,
    LicenseActions: LicenseActions,
    BatchUpdate: BatchUpdate
  },
  props: {
    rows: {
      type: Array,
      required: true
    },
    nonce: {
      type: String,
      required: true
    },
    // Base URLs for the install/update actions, built server-side with linkTo()
    // so they keep whatever idSite/period/date params the admin page carries.
    // slug/version travel as hidden POST fields, mirroring the pre-Vue twig forms.
    installUrl: {
      type: String,
      required: true
    },
    updateUrl: {
      type: String,
      required: true
    },
    activateUrl: {
      type: String,
      default: ''
    },
    dbHeld: {
      type: Array,
      default: () => []
    },
    claimUrl: {
      type: String,
      default: ''
    },
    buyUrl: {
      type: String,
      default: ''
    },
    licensingAvailable: {
      type: Boolean,
      default: false
    },
    // "Update several at once" (BatchUpdate).
    selfSlug: {
      type: String,
      default: 'FeinwerkMarketplaceConnector'
    },
    batchNonce: {
      type: String,
      default: ''
    },
    batchUpdateUrl: {
      type: String,
      default: ''
    },
    batchFinishUrl: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      selected: []
    };
  },
  computed: {
    batchCandidates() {
      return this.rows.filter(r => canBatch(r, this.selfSlug));
    },
    allSelected() {
      const all = this.batchCandidates.length;
      return all > 0 && this.selected.length === all;
    }
  },
  methods: {
    translate: external_CoreHome_["translate"],
    activateLink: activateLink,
    canBatch(row) {
      return canBatch(row, this.selfSlug);
    },
    toggleAll() {
      this.selected = this.allSelected ? [] : this.batchCandidates.map(r => r.slug);
    },
    monogram(name) {
      return (name || '?').trim().charAt(0).toUpperCase();
    },
    statusLabel(status) {
      const map = {
        installed: 'FeinwerkMarketplaceConnector_StatusInstalled',
        update_available: 'FeinwerkMarketplaceConnector_StatusUpdate',
        not_installed: 'FeinwerkMarketplaceConnector_StatusNotInstalled'
      };
      return Object(external_CoreHome_["translate"])(map[status] || 'FeinwerkMarketplaceConnector_StatusNotInstalled');
    },
    // A paid plugin without a license that the account cannot claim: buy it from the store.
    canBuy(row) {
      return this.licensingAvailable && row.needsLicense && !row.license && !row.licenseClaimable && !!row.latestVersion && !row.devCheckout;
    },
    actionFor(row) {
      if (!row.latestVersion) return null;
      if (row.devCheckout) return null;
      if (row.switching) return null;
      if (row.needsLicense && !row.license) return null;
      if (row.status === 'not_installed') return Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_Install');
      if (row.status === 'update_available') {
        return Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_UpdateTo', row.latestVersion);
      }
      return null;
    },
    actionUrl(row) {
      return row.status === 'update_available' ? this.updateUrl : this.installUrl;
    }
  }
}));
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/ManageTable/ManageTable.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/ManageTable/ManageTable.vue



ManageTablevue_type_script_lang_ts.render = ManageTablevue_type_template_id_6f9f5b1c_render

/* harmony default export */ var ManageTable = (ManageTablevue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/BrowseGrid/BrowseGrid.vue?vue&type=template&id=f1fa957e

const BrowseGridvue_type_template_id_f1fa957e_hoisted_1 = {
  class: "fwmc-browse"
};
const BrowseGridvue_type_template_id_f1fa957e_hoisted_2 = {
  class: "fwmc-filters"
};
const BrowseGridvue_type_template_id_f1fa957e_hoisted_3 = ["placeholder", "aria-label"];
const BrowseGridvue_type_template_id_f1fa957e_hoisted_4 = ["aria-label"];
const BrowseGridvue_type_template_id_f1fa957e_hoisted_5 = {
  value: ""
};
const BrowseGridvue_type_template_id_f1fa957e_hoisted_6 = ["value"];
const BrowseGridvue_type_template_id_f1fa957e_hoisted_7 = ["aria-label"];
const BrowseGridvue_type_template_id_f1fa957e_hoisted_8 = {
  value: ""
};
const BrowseGridvue_type_template_id_f1fa957e_hoisted_9 = {
  value: "free"
};
const BrowseGridvue_type_template_id_f1fa957e_hoisted_10 = {
  value: "paid"
};
const BrowseGridvue_type_template_id_f1fa957e_hoisted_11 = ["aria-label"];
const BrowseGridvue_type_template_id_f1fa957e_hoisted_12 = {
  value: ""
};
const BrowseGridvue_type_template_id_f1fa957e_hoisted_13 = {
  value: "name"
};
const BrowseGridvue_type_template_id_f1fa957e_hoisted_14 = {
  value: "recent"
};
const BrowseGridvue_type_template_id_f1fa957e_hoisted_15 = {
  class: "fwmc-count",
  "aria-live": "polite"
};
const BrowseGridvue_type_template_id_f1fa957e_hoisted_16 = {
  key: 0,
  class: "fwmc-grid"
};
const BrowseGridvue_type_template_id_f1fa957e_hoisted_17 = {
  key: 1,
  class: "fwmc-empty"
};
function BrowseGridvue_type_template_id_f1fa957e_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_PluginCard = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("PluginCard");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", BrowseGridvue_type_template_id_f1fa957e_hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", BrowseGridvue_type_template_id_f1fa957e_hoisted_2, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "search",
    "onUpdate:modelValue": _cache[0] || (_cache[0] = $event => _ctx.search = $event),
    placeholder: _ctx.translate('FeinwerkMarketplaceConnector_SearchPlaceholder'),
    "aria-label": _ctx.translate('FeinwerkMarketplaceConnector_SearchPlaceholder'),
    class: "fwmc-search"
  }, null, 8, BrowseGridvue_type_template_id_f1fa957e_hoisted_3), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelText"], _ctx.search]]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("select", {
    "onUpdate:modelValue": _cache[1] || (_cache[1] = $event => _ctx.category = $event),
    class: "fwmc-filter",
    "aria-label": _ctx.translate('FeinwerkMarketplaceConnector_FilterCategory')
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("option", BrowseGridvue_type_template_id_f1fa957e_hoisted_5, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_AllCategories')), 1), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.categories, c => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("option", {
      key: c,
      value: c
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.categoryLabel(c)), 9, BrowseGridvue_type_template_id_f1fa957e_hoisted_6);
  }), 128))], 8, BrowseGridvue_type_template_id_f1fa957e_hoisted_4), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelSelect"], _ctx.category]]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("select", {
    "onUpdate:modelValue": _cache[2] || (_cache[2] = $event => _ctx.tier = $event),
    class: "fwmc-filter",
    "aria-label": _ctx.translate('FeinwerkMarketplaceConnector_FilterTier')
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("option", BrowseGridvue_type_template_id_f1fa957e_hoisted_8, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_AllTiers')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("option", BrowseGridvue_type_template_id_f1fa957e_hoisted_9, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_TierFree')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("option", BrowseGridvue_type_template_id_f1fa957e_hoisted_10, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_TierPaid')), 1)], 8, BrowseGridvue_type_template_id_f1fa957e_hoisted_7), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelSelect"], _ctx.tier]]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("select", {
    "onUpdate:modelValue": _cache[3] || (_cache[3] = $event => _ctx.sort = $event),
    class: "fwmc-filter",
    "aria-label": _ctx.translate('FeinwerkMarketplaceConnector_SortLabel')
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("option", BrowseGridvue_type_template_id_f1fa957e_hoisted_12, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_SortRecommended')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("option", BrowseGridvue_type_template_id_f1fa957e_hoisted_13, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_SortName')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("option", BrowseGridvue_type_template_id_f1fa957e_hoisted_14, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_SortRecent')), 1)], 8, BrowseGridvue_type_template_id_f1fa957e_hoisted_11), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelSelect"], _ctx.sort]])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", BrowseGridvue_type_template_id_f1fa957e_hoisted_15, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.countText), 1), _ctx.filtered.length ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", BrowseGridvue_type_template_id_f1fa957e_hoisted_16, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.filtered, row => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_PluginCard, {
      key: row.slug,
      row: row,
      "category-label": _ctx.categoryLabel(row.category),
      onOpen: $event => _ctx.$emit('open', row.slug)
    }, null, 8, ["row", "category-label", "onOpen"]);
  }), 128))])) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", BrowseGridvue_type_template_id_f1fa957e_hoisted_17, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_NoResults')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "button",
    class: "btn btn-flat",
    onClick: _cache[4] || (_cache[4] = (...args) => _ctx.reset && _ctx.reset(...args))
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ResetFilters')), 1)]))]);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/BrowseGrid/BrowseGrid.vue?vue&type=template&id=f1fa957e

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/PluginCard/PluginCard.vue?vue&type=template&id=1a0cc0ea

const PluginCardvue_type_template_id_1a0cc0ea_hoisted_1 = {
  class: "fwmc-card"
};
const PluginCardvue_type_template_id_1a0cc0ea_hoisted_2 = {
  class: "fwmc-card-media"
};
const PluginCardvue_type_template_id_1a0cc0ea_hoisted_3 = ["src"];
const PluginCardvue_type_template_id_1a0cc0ea_hoisted_4 = {
  key: 1,
  class: "fwmc-avatar fwmc-avatar--lg",
  "aria-hidden": "true"
};
const PluginCardvue_type_template_id_1a0cc0ea_hoisted_5 = {
  class: "fwmc-card-body"
};
const PluginCardvue_type_template_id_1a0cc0ea_hoisted_6 = {
  class: "fwmc-card-title"
};
const PluginCardvue_type_template_id_1a0cc0ea_hoisted_7 = {
  key: 0,
  class: "fwmc-card-desc"
};
const PluginCardvue_type_template_id_1a0cc0ea_hoisted_8 = {
  class: "fwmc-card-foot"
};
const PluginCardvue_type_template_id_1a0cc0ea_hoisted_9 = {
  key: 0,
  class: "fwmc-meta"
};
const PluginCardvue_type_template_id_1a0cc0ea_hoisted_10 = {
  key: 1,
  class: "fwmc-price"
};
const PluginCardvue_type_template_id_1a0cc0ea_hoisted_11 = {
  key: 2,
  class: "fwmc-meta fwmc-card-na"
};
const PluginCardvue_type_template_id_1a0cc0ea_hoisted_12 = {
  key: 3,
  class: "fwmc-badge fwmc-status--installed"
};
const PluginCardvue_type_template_id_1a0cc0ea_hoisted_13 = ["title"];
const PluginCardvue_type_template_id_1a0cc0ea_hoisted_14 = {
  key: 5,
  class: "fwmc-badge fwmc-status--update_available"
};
function PluginCardvue_type_template_id_1a0cc0ea_render(_ctx, _cache, $props, $setup, $data, $options) {
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", PluginCardvue_type_template_id_1a0cc0ea_hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", PluginCardvue_type_template_id_1a0cc0ea_hoisted_2, [_ctx.row.artworkUrl && !_ctx.artworkFailed ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("img", {
    key: 0,
    src: _ctx.proxied(_ctx.row.artworkUrl),
    alt: "",
    loading: "lazy",
    onError: _cache[0] || (_cache[0] = $event => _ctx.artworkFailed = true)
  }, null, 40, PluginCardvue_type_template_id_1a0cc0ea_hoisted_3)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", PluginCardvue_type_template_id_1a0cc0ea_hoisted_4, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.monogram), 1))]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", PluginCardvue_type_template_id_1a0cc0ea_hoisted_5, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h3", PluginCardvue_type_template_id_1a0cc0ea_hoisted_6, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "button",
    class: "fwmc-card-link",
    onClick: _cache[1] || (_cache[1] = $event => _ctx.$emit('open'))
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.row.displayName), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", {
    class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])(["fwmc-badge", 'fwmc-badge--' + _ctx.row.tier])
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tierLabel), 3)]), _ctx.row.description ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", PluginCardvue_type_template_id_1a0cc0ea_hoisted_7, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.row.description), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", PluginCardvue_type_template_id_1a0cc0ea_hoisted_8, [_ctx.row.category ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", PluginCardvue_type_template_id_1a0cc0ea_hoisted_9, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.categoryLabel || _ctx.row.category), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.row.priceFrom ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", PluginCardvue_type_template_id_1a0cc0ea_hoisted_10, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_PriceFrom', _ctx.amount(_ctx.row.priceFrom.amount_eur), _ctx.intervalLabel(_ctx.row.priceFrom.interval))), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.row.availableHere === false ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", PluginCardvue_type_template_id_1a0cc0ea_hoisted_11, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_NotAvailableHere')), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.row.status === 'installed' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", PluginCardvue_type_template_id_1a0cc0ea_hoisted_12, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_StatusInstalled')), 1)) : _ctx.row.switching ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", {
    key: 4,
    class: "fwmc-badge fwmc-status--not_installed",
    title: _ctx.translate('FeinwerkMarketplaceConnector_SwitchingHint')
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_Switching')), 9, PluginCardvue_type_template_id_1a0cc0ea_hoisted_13)) : _ctx.row.status === 'update_available' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", PluginCardvue_type_template_id_1a0cc0ea_hoisted_14, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_StatusUpdate')), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])])]);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/PluginCard/PluginCard.vue?vue&type=template&id=1a0cc0ea

// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/intervals.ts

/** Human label for a plan interval (monthly | yearly | two_years | once). */
function intervalLabel(interval) {
  const map = {
    monthly: 'FeinwerkMarketplaceConnector_IntervalMonthly',
    yearly: 'FeinwerkMarketplaceConnector_IntervalYearly',
    two_years: 'FeinwerkMarketplaceConnector_IntervalTwoYears',
    once: 'FeinwerkMarketplaceConnector_IntervalOnce'
  };
  return Object(external_CoreHome_["translate"])(map[interval] || 'FeinwerkMarketplaceConnector_IntervalMonthly');
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/amount.ts
/**
 * A euro amount from the store ("1299.00") in the admin's number format ("1.299,00" in
 * German), through Matomo's own global NumberFormatter (5.0 to 6; the admin pages set no
 * html lang). Without it: the plain amount.
 */
function amount(value) {
  if (value === null || value === undefined) {
    return '';
  }
  const n = Number(value);
  if (value === '' || !Number.isFinite(n)) {
    return String(value);
  }
  try {
    const formatter = window.NumberFormatter;
    if (formatter && typeof formatter.formatNumber === 'function') {
      const formatted = formatter.formatNumber(n, 2, 2);
      if (typeof formatted === 'string' && formatted !== '') {
        return formatted;
      }
    }
  } catch (e) {
    // fall back to the plain amount
  }
  return n.toFixed(2);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/proxied.ts
/** Rewrite a marketplace image URL through the CSP-safe connector proxy. */
function proxied(url) {
  if (!url) return null;
  return `index.php?module=FeinwerkMarketplaceConnector&action=image&src=${encodeURIComponent(url)}`;
}
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/PluginCard/PluginCard.vue?vue&type=script&lang=ts





/* harmony default export */ var PluginCardvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  emits: ['open'],
  props: {
    row: {
      type: Object,
      required: true
    },
    categoryLabel: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      artworkFailed: false
    };
  },
  computed: {
    monogram() {
      return (this.row.displayName || '?').trim().charAt(0).toUpperCase();
    },
    tierLabel() {
      return Object(external_CoreHome_["translate"])(this.row.tier === 'paid' ? 'FeinwerkMarketplaceConnector_TierPaid' : 'FeinwerkMarketplaceConnector_TierFree');
    }
  },
  methods: {
    translate: external_CoreHome_["translate"],
    intervalLabel: intervalLabel,
    amount: amount,
    proxied: proxied
  }
}));
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/PluginCard/PluginCard.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/PluginCard/PluginCard.vue



PluginCardvue_type_script_lang_ts.render = PluginCardvue_type_template_id_1a0cc0ea_render

/* harmony default export */ var PluginCard = (PluginCardvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/BrowseGrid/BrowseGrid.vue?vue&type=script&lang=ts



/* harmony default export */ var BrowseGridvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  components: {
    PluginCard: PluginCard
  },
  emits: ['open'],
  props: {
    rows: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      search: '',
      category: '',
      tier: '',
      sort: ''
    };
  },
  computed: {
    categories() {
      return [...new Set(this.rows.map(r => r.category).filter(Boolean))].sort((a, b) => this.categoryLabel(a).localeCompare(this.categoryLabel(b)));
    },
    filtered() {
      const q = this.search.trim().toLowerCase();
      const rows = this.rows.filter(r => {
        if (this.category && r.category !== this.category) return false;
        if (this.tier && r.tier !== this.tier) return false;
        if (!q) return true;
        const haystack = [r.displayName, r.description, r.slug, r.category, r.category ? this.categoryLabel(r.category) : ''].join(' ').toLowerCase();
        return haystack.includes(q);
      });
      if (this.sort === 'name') {
        return [...rows].sort((a, b) => String(a.displayName).localeCompare(String(b.displayName)));
      }
      if (this.sort === 'recent') {
        // ISO 8601 dates sort as strings; plugins without a date go last.
        return [...rows].sort((a, b) => String(b.releasedAt || '').localeCompare(String(a.releasedAt || '')));
      }
      return rows;
    },
    countText() {
      const n = this.filtered.length;
      return n === 1 ? Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_ResultCountOne') : Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_ResultCount', String(n));
    }
  },
  methods: {
    translate: external_CoreHome_["translate"],
    // The store sends category codes (analytics, monitoring, ...): show the translation, else
    // the code. Matomo's translate() answers an unknown key with "The string ... was not
    // loaded", so only keys in Matomo's table (piwik_translations, 5.0 to 6) are translated.
    categoryLabel(code) {
      if (!code) return '';
      const key = `FeinwerkMarketplaceConnector_Category_${String(code).toLowerCase()}`;
      const known = window.piwik_translations || {};
      if (typeof known[key] === 'string') {
        return Object(external_CoreHome_["translate"])(key);
      }
      const text = String(code);
      return text.charAt(0).toUpperCase() + text.slice(1);
    },
    reset() {
      this.search = '';
      this.category = '';
      this.tier = '';
    }
  }
}));
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/BrowseGrid/BrowseGrid.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/BrowseGrid/BrowseGrid.vue



BrowseGridvue_type_script_lang_ts.render = BrowseGridvue_type_template_id_f1fa957e_render

/* harmony default export */ var BrowseGrid = (BrowseGridvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/TaskList/TaskList.vue?vue&type=template&id=33665b64

const TaskListvue_type_template_id_33665b64_hoisted_1 = {
  class: "fwmc-tasks"
};
const TaskListvue_type_template_id_33665b64_hoisted_2 = {
  key: 1,
  class: "fwmc-empty"
};
const TaskListvue_type_template_id_33665b64_hoisted_3 = {
  key: 2,
  class: "fwmc-task-list"
};
const TaskListvue_type_template_id_33665b64_hoisted_4 = {
  class: "fwmc-task-text"
};
const TaskListvue_type_template_id_33665b64_hoisted_5 = {
  key: 0,
  class: "fwmc-task-code"
};
const TaskListvue_type_template_id_33665b64_hoisted_6 = {
  class: "fwmc-task-action"
};
const TaskListvue_type_template_id_33665b64_hoisted_7 = ["action"];
const TaskListvue_type_template_id_33665b64_hoisted_8 = ["value"];
const TaskListvue_type_template_id_33665b64_hoisted_9 = {
  type: "submit",
  class: "btn"
};
const TaskListvue_type_template_id_33665b64_hoisted_10 = ["href"];
const TaskListvue_type_template_id_33665b64_hoisted_11 = {
  key: 2,
  class: "btn",
  href: "index.php?module=CoreUpdater"
};
const TaskListvue_type_template_id_33665b64_hoisted_12 = ["href"];
const TaskListvue_type_template_id_33665b64_hoisted_13 = ["href"];
const TaskListvue_type_template_id_33665b64_hoisted_14 = ["href"];
const TaskListvue_type_template_id_33665b64_hoisted_15 = ["onClick"];
function TaskListvue_type_template_id_33665b64_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_BatchUpdate = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("BatchUpdate");
  const _component_InstallForm = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("InstallForm");
  const _component_LicenseActions = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("LicenseActions");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", TaskListvue_type_template_id_33665b64_hoisted_1, [_ctx.batchCandidates.length > 1 && _ctx.batchNonce ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_BatchUpdate, {
    key: 0,
    candidates: _ctx.batchCandidates,
    nonce: _ctx.batchNonce,
    "update-url": _ctx.batchUpdateUrl,
    "finish-url": _ctx.batchFinishUrl
  }, null, 8, ["candidates", "nonce", "update-url", "finish-url"])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), !_ctx.items.length ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", TaskListvue_type_template_id_33665b64_hoisted_2, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('TasksEmpty')), 1)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("ul", TaskListvue_type_template_id_33665b64_hoisted_3, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.items, item => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("li", {
      key: item.kind + ':' + (item.slug || ''),
      class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])(["fwmc-task", 'fwmc-task--' + item.severity])
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", TaskListvue_type_template_id_33665b64_hoisted_4, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.text(item)), 1), item.kind === 'db_update' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("code", TaskListvue_type_template_id_33665b64_hoisted_5, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.consoleCommand), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", TaskListvue_type_template_id_33665b64_hoisted_6, [item.kind === 'connection_auth' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("form", {
      key: 0,
      method: "post",
      action: _ctx.connectUrl
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
      type: "hidden",
      name: "nonce",
      value: _ctx.nonce
    }, null, 8, TaskListvue_type_template_id_33665b64_hoisted_8), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", TaskListvue_type_template_id_33665b64_hoisted_9, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('ConnectAgain')), 1)], 8, TaskListvue_type_template_id_33665b64_hoisted_7)) : item.kind === 'connection_offline' && _ctx.safe(_ctx.offlineDocsUrl) ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
      key: 1,
      href: _ctx.offlineDocsUrl,
      target: "_blank",
      rel: "noreferrer noopener"
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('OfflineLink')) + " ↗", 9, TaskListvue_type_template_id_33665b64_hoisted_10)) : item.kind === 'db_update' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", TaskListvue_type_template_id_33665b64_hoisted_11, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('Task_db_update_page')), 1)) : item.kind === 'switch_pending' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
      key: 3,
      class: "btn",
      href: _ctx.switchNowUrl
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('SwapNow')), 9, TaskListvue_type_template_id_33665b64_hoisted_12)) : ['update', 'self_update', 'db_held'].includes(item.kind) && _ctx.row(item) ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_InstallForm, {
      key: 4,
      "action-url": _ctx.updateUrl,
      nonce: _ctx.nonce,
      slug: item.slug,
      version: _ctx.row(item).latestVersion,
      label: _ctx.tr('UpdateTo', _ctx.row(item).latestVersion),
      "plugin-name": _ctx.row(item).displayName,
      "is-update": true,
      "db-change": _ctx.row(item).dbChange || null,
      held: item.kind === 'db_held'
    }, null, 8, ["action-url", "nonce", "slug", "version", "label", "plugin-name", "db-change", "held"])) : item.kind === 'license_missing' && _ctx.row(item) ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 5
    }, [_ctx.licensingAvailable && _ctx.row(item).licenseClaimable ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_LicenseActions, {
      key: 0,
      kind: "claim",
      url: _ctx.claimUrl,
      nonce: _ctx.nonce,
      slug: item.slug
    }, null, 8, ["url", "nonce", "slug"])) : _ctx.licensingAvailable ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_LicenseActions, {
      key: 1,
      kind: "buy",
      url: _ctx.buyUrl,
      nonce: _ctx.nonce,
      slug: item.slug
    }, null, 8, ["url", "nonce", "slug"])) : _ctx.row(item).url && _ctx.safe(_ctx.row(item).url) ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
      key: 2,
      href: _ctx.row(item).url,
      target: "_blank",
      rel: "noreferrer noopener"
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('LearnMore')) + " ↗ ", 9, TaskListvue_type_template_id_33665b64_hoisted_13)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)], 64)) : item.kind === 'license_expiring' && _ctx.safe(_ctx.accountUrl) ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
      key: 6,
      href: _ctx.accountUrl,
      target: "_blank",
      rel: "noreferrer noopener"
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('Task_renew')) + " ↗", 9, TaskListvue_type_template_id_33665b64_hoisted_14)) : item.kind === 'license_claimable' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("button", {
      key: 7,
      type: "button",
      class: "btn btn-flat",
      onClick: $event => _ctx.$emit('open', item.slug)
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('Task_view')), 9, TaskListvue_type_template_id_33665b64_hoisted_15)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])], 2);
  }), 128))]))]);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/TaskList/TaskList.vue?vue&type=template&id=33665b64

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/TaskList/TaskList.vue?vue&type=script&lang=ts






const PREFIX = 'FeinwerkMarketplaceConnector_';
/* harmony default export */ var TaskListvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  components: {
    InstallForm: InstallForm,
    LicenseActions: LicenseActions,
    BatchUpdate: BatchUpdate
  },
  emits: ['open'],
  props: {
    items: {
      type: Array,
      required: true
    },
    rows: {
      type: Array,
      required: true
    },
    nonce: {
      type: String,
      required: true
    },
    updateUrl: {
      type: String,
      required: true
    },
    claimUrl: {
      type: String,
      default: ''
    },
    buyUrl: {
      type: String,
      default: ''
    },
    connectUrl: {
      type: String,
      default: ''
    },
    switchNowUrl: {
      type: String,
      default: ''
    },
    accountUrl: {
      type: String,
      default: ''
    },
    offlineDocsUrl: {
      type: String,
      default: ''
    },
    consoleCommand: {
      type: String,
      default: ''
    },
    licensingAvailable: {
      type: Boolean,
      default: false
    },
    selfSlug: {
      type: String,
      default: 'FeinwerkMarketplaceConnector'
    },
    batchNonce: {
      type: String,
      default: ''
    },
    batchUpdateUrl: {
      type: String,
      default: ''
    },
    batchFinishUrl: {
      type: String,
      default: ''
    }
  },
  computed: {
    // The updates of the list (plain and held for their database change), as rows.
    batchCandidates() {
      const slugs = this.items.filter(i => i.kind === 'update' || i.kind === 'db_held').map(i => i.slug);
      return this.rows.filter(r => slugs.includes(r.slug) && canBatch(r, this.selfSlug));
    }
  },
  methods: {
    tr(key, ...args) {
      return Object(external_CoreHome_["translate"])(`${PREFIX}${key}`, ...args);
    },
    row(item) {
      return this.rows.find(r => r.slug === item.slug) || null;
    },
    name(item) {
      const row = this.row(item);
      return row ? row.displayName : String(item.slug || '');
    },
    // Only an http(s) address is bound as a link.
    safe(url) {
      return /^https?:\/\//i.test(url || '');
    },
    date(value) {
      try {
        return new Date(`${value}T12:00:00`).toLocaleDateString(external_CoreHome_["Matomo"].language || undefined);
      } catch (e) {
        return value;
      }
    },
    text(item) {
      const row = this.row(item);
      switch (item.kind) {
        case 'connection_auth':
        case 'connection_offline':
        case 'switch_pending':
          return this.tr(`Task_${item.kind}`);
        case 'db_update':
          return this.tr('Task_db_update', (item.components || []).join(', '));
        case 'self_update':
          return this.tr('SelfUpdateAvailable', (row === null || row === void 0 ? void 0 : row.installedVersion) || '', (row === null || row === void 0 ? void 0 : row.latestVersion) || '');
        case 'update':
          return this.tr('Task_update', this.name(item), (row === null || row === void 0 ? void 0 : row.installedVersion) || '', (row === null || row === void 0 ? void 0 : row.latestVersion) || '');
        case 'db_held':
          return this.tr('Task_db_held', this.name(item), (row === null || row === void 0 ? void 0 : row.latestVersion) || '');
        case 'license_expiring':
          return this.tr('Task_license_expiring', this.name(item), this.date(item.date || ''));
        default:
          return this.tr(`Task_${item.kind}`, this.name(item));
      }
    }
  }
}));
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/TaskList/TaskList.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/TaskList/TaskList.vue



TaskListvue_type_script_lang_ts.render = TaskListvue_type_template_id_33665b64_render

/* harmony default export */ var TaskList = (TaskListvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/LicenseTable/LicenseTable.vue?vue&type=template&id=0ab62fba

const LicenseTablevue_type_template_id_0ab62fba_hoisted_1 = {
  class: "fwmc-licenses"
};
const LicenseTablevue_type_template_id_0ab62fba_hoisted_2 = {
  class: "fwmc-licenses-intro"
};
const LicenseTablevue_type_template_id_0ab62fba_hoisted_3 = ["href"];
const LicenseTablevue_type_template_id_0ab62fba_hoisted_4 = {
  key: 0,
  class: "fwmc-empty"
};
const LicenseTablevue_type_template_id_0ab62fba_hoisted_5 = {
  key: 1,
  class: "fwmc-tablewrap"
};
const LicenseTablevue_type_template_id_0ab62fba_hoisted_6 = {
  class: "entityTable dataTable fwmc-table"
};
const LicenseTablevue_type_template_id_0ab62fba_hoisted_7 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, null, -1);
const LicenseTablevue_type_template_id_0ab62fba_hoisted_8 = ["onClick"];
const LicenseTablevue_type_template_id_0ab62fba_hoisted_9 = {
  key: 0,
  class: "fwmc-badge fwmc-badge--license-ok"
};
const LicenseTablevue_type_template_id_0ab62fba_hoisted_10 = {
  key: 1,
  class: "fwmc-badge fwmc-badge--license-missing"
};
const LicenseTablevue_type_template_id_0ab62fba_hoisted_11 = {
  key: 2
};
function LicenseTablevue_type_template_id_0ab62fba_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_LicenseActions = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("LicenseActions");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", LicenseTablevue_type_template_id_0ab62fba_hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", LicenseTablevue_type_template_id_0ab62fba_hoisted_2, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_LicensesIntro')) + " ", 1), _ctx.safeAccountUrl ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
    key: 0,
    href: _ctx.safeAccountUrl,
    target: "_blank",
    rel: "noreferrer noopener"
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_LicensesAccount')) + " ↗ ", 9, LicenseTablevue_type_template_id_0ab62fba_hoisted_3)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), !_ctx.paid.length ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", LicenseTablevue_type_template_id_0ab62fba_hoisted_4, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_LicensesEmpty')), 1)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", LicenseTablevue_type_template_id_0ab62fba_hoisted_5, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("table", LicenseTablevue_type_template_id_0ab62fba_hoisted_6, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("thead", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("tr", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ColumnPlugin')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ColumnLicense')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ColumnInstalled')), 1), LicenseTablevue_type_template_id_0ab62fba_hoisted_7])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("tbody", null, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.paid, row => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("tr", {
      key: row.slug
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
      type: "button",
      class: "fwmc-link-button",
      onClick: $event => _ctx.$emit('open', row.slug)
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(row.displayName), 9, LicenseTablevue_type_template_id_0ab62fba_hoisted_8)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, [row.license ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", LicenseTablevue_type_template_id_0ab62fba_hoisted_9, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_LicenseActive')) + " ", 1), row.license.valid_until ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 0
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" · " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_LicenseUntil', _ctx.date(row.license.valid_until))), 1)], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])) : row.needsLicense ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", LicenseTablevue_type_template_id_0ab62fba_hoisted_10, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_NoLicense')), 1)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", LicenseTablevue_type_template_id_0ab62fba_hoisted_11, "-"))]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(row.installedVersion || _ctx.translate('FeinwerkMarketplaceConnector_NotInstalled')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, [_ctx.licensingAvailable && row.needsLicense && row.licenseClaimable ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_LicenseActions, {
      key: 0,
      kind: "claim",
      url: _ctx.claimUrl,
      nonce: _ctx.nonce,
      slug: row.slug
    }, null, 8, ["url", "nonce", "slug"])) : _ctx.licensingAvailable && row.needsLicense ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_LicenseActions, {
      key: 1,
      kind: "buy",
      url: _ctx.buyUrl,
      nonce: _ctx.nonce,
      slug: row.slug
    }, null, 8, ["url", "nonce", "slug"])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])]);
  }), 128))])])]))]);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/LicenseTable/LicenseTable.vue?vue&type=template&id=0ab62fba

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/LicenseTable/LicenseTable.vue?vue&type=script&lang=ts



/* harmony default export */ var LicenseTablevue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  components: {
    LicenseActions: LicenseActions
  },
  emits: ['open'],
  props: {
    rows: {
      type: Array,
      required: true
    },
    nonce: {
      type: String,
      required: true
    },
    claimUrl: {
      type: String,
      default: ''
    },
    buyUrl: {
      type: String,
      default: ''
    },
    accountUrl: {
      type: String,
      default: ''
    },
    licensingAvailable: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    paid() {
      return this.rows.filter(r => r.tier === 'paid' || r.needsLicense || r.license);
    },
    safeAccountUrl() {
      return /^https?:\/\//i.test(this.accountUrl) ? this.accountUrl : '';
    }
  },
  methods: {
    translate: external_CoreHome_["translate"],
    date(value) {
      const day = String(value || '').slice(0, 10);
      try {
        return new Date(`${day}T12:00:00`).toLocaleDateString(external_CoreHome_["Matomo"].language || undefined);
      } catch (e) {
        return value;
      }
    }
  }
}));
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/LicenseTable/LicenseTable.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/LicenseTable/LicenseTable.vue



LicenseTablevue_type_script_lang_ts.render = LicenseTablevue_type_template_id_0ab62fba_render

/* harmony default export */ var LicenseTable = (LicenseTablevue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/PromoBanner/PromoBanner.vue?vue&type=template&id=4150a562

const PromoBannervue_type_template_id_4150a562_hoisted_1 = {
  key: 0,
  class: "fwmc-promo-offnote",
  "aria-live": "polite"
};
const PromoBannervue_type_template_id_4150a562_hoisted_2 = ["href"];
const PromoBannervue_type_template_id_4150a562_hoisted_3 = ["aria-label"];
const PromoBannervue_type_template_id_4150a562_hoisted_4 = {
  class: "fwmc-promo-art"
};
const PromoBannervue_type_template_id_4150a562_hoisted_5 = ["src"];
const PromoBannervue_type_template_id_4150a562_hoisted_6 = {
  key: 1,
  class: "fwmc-avatar fwmc-avatar--lg",
  "aria-hidden": "true"
};
const PromoBannervue_type_template_id_4150a562_hoisted_7 = {
  class: "fwmc-promo-body"
};
const PromoBannervue_type_template_id_4150a562_hoisted_8 = {
  class: "fwmc-promo-label"
};
const PromoBannervue_type_template_id_4150a562_hoisted_9 = {
  class: "fwmc-promo-title"
};
const PromoBannervue_type_template_id_4150a562_hoisted_10 = {
  key: 0,
  class: "fwmc-promo-text"
};
const PromoBannervue_type_template_id_4150a562_hoisted_11 = {
  class: "fwmc-promo-actions"
};
const PromoBannervue_type_template_id_4150a562_hoisted_12 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", {
  "aria-hidden": "true"
}, "→", -1);
const PromoBannervue_type_template_id_4150a562_hoisted_13 = ["href"];
const PromoBannervue_type_template_id_4150a562_hoisted_14 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", {
  "aria-hidden": "true"
}, "→", -1);
const PromoBannervue_type_template_id_4150a562_hoisted_15 = {
  key: 2,
  class: "fwmc-promo-price"
};
const PromoBannervue_type_template_id_4150a562_hoisted_16 = ["disabled"];
const PromoBannervue_type_template_id_4150a562_hoisted_17 = ["href"];
const PromoBannervue_type_template_id_4150a562_hoisted_18 = ["aria-label", "title"];
const PromoBannervue_type_template_id_4150a562_hoisted_19 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", {
  "aria-hidden": "true"
}, "×", -1);
const PromoBannervue_type_template_id_4150a562_hoisted_20 = [PromoBannervue_type_template_id_4150a562_hoisted_19];
function PromoBannervue_type_template_id_4150a562_render(_ctx, _cache, $props, $setup, $data, $options) {
  return _ctx.switchedOff ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", PromoBannervue_type_template_id_4150a562_hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('PromoOffDone')) + " ", 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("a", {
    href: _ctx.settingsUrl
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('PromoOffUndo')), 9, PromoBannervue_type_template_id_4150a562_hoisted_2)])) : _ctx.promo && _ctx.promo.slug && !_ctx.hidden ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("aside", {
    key: 1,
    class: "fwmc-promo",
    "aria-label": _ctx.tr('PromoLabel')
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", PromoBannervue_type_template_id_4150a562_hoisted_4, [_ctx.promo.artworkUrl && !_ctx.artworkFailed ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("img", {
    key: 0,
    src: _ctx.proxied(_ctx.promo.artworkUrl),
    alt: "",
    onError: _cache[0] || (_cache[0] = $event => _ctx.artworkFailed = true)
  }, null, 40, PromoBannervue_type_template_id_4150a562_hoisted_5)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", PromoBannervue_type_template_id_4150a562_hoisted_6, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.monogram), 1))]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", PromoBannervue_type_template_id_4150a562_hoisted_7, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", PromoBannervue_type_template_id_4150a562_hoisted_8, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('PromoLabel')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h3", PromoBannervue_type_template_id_4150a562_hoisted_9, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.promo.name), 1), _ctx.promo.description ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", PromoBannervue_type_template_id_4150a562_hoisted_10, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.promo.description), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", PromoBannervue_type_template_id_4150a562_hoisted_11, [_ctx.inline ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("button", {
    key: 0,
    type: "button",
    class: "btn fwmc-promo-cta",
    onClick: _cache[1] || (_cache[1] = $event => _ctx.$emit('open', _ctx.promo.slug))
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('PromoCta')) + " ", 1), PromoBannervue_type_template_id_4150a562_hoisted_12])) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
    key: 1,
    class: "btn fwmc-promo-cta",
    href: _ctx.screenUrl
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('PromoCta')) + " ", 1), PromoBannervue_type_template_id_4150a562_hoisted_14], 8, PromoBannervue_type_template_id_4150a562_hoisted_13)), _ctx.price ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", PromoBannervue_type_template_id_4150a562_hoisted_15, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.price), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.promo.offUrl && _ctx.promo.offNonce ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("button", {
    key: 3,
    type: "button",
    class: "fwmc-promo-off",
    disabled: _ctx.switching,
    onClick: _cache[2] || (_cache[2] = (...args) => _ctx.switchOff && _ctx.switchOff(...args))
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.switching ? _ctx.tr('Working') : _ctx.tr('PromoSettings')), 9, PromoBannervue_type_template_id_4150a562_hoisted_16)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
    key: 4,
    class: "fwmc-promo-off",
    href: _ctx.settingsUrl
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('PromoSettings')), 9, PromoBannervue_type_template_id_4150a562_hoisted_17))])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "button",
    class: "fwmc-promo-close",
    "aria-label": _ctx.tr('PromoClose'),
    title: _ctx.tr('PromoClose'),
    onClick: _cache[3] || (_cache[3] = (...args) => _ctx.dismiss && _ctx.dismiss(...args))
  }, PromoBannervue_type_template_id_4150a562_hoisted_20, 8, PromoBannervue_type_template_id_4150a562_hoisted_18)], 8, PromoBannervue_type_template_id_4150a562_hoisted_3)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/PromoBanner/PromoBanner.vue?vue&type=template&id=4150a562

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/PromoBanner/PromoBanner.vue?vue&type=script&lang=ts





const HIDE_DAYS = 30;
function storageKey(slug) {
  return `fwmc.promo.hidden.${slug}`;
}
/* harmony default export */ var PromoBannervue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  emits: ['open'],
  props: {
    promo: {
      type: Object,
      default: null
    },
    // On the Discover tab the button opens the plugin window right there.
    inline: {
      type: Boolean,
      default: false
    }
  },
  data() {
    let hidden = false;
    try {
      var _this$promo;
      const at = Number(window.localStorage.getItem(storageKey(((_this$promo = this.promo) === null || _this$promo === void 0 ? void 0 : _this$promo.slug) || '')) || 0);
      hidden = at > 0 && Date.now() - at < HIDE_DAYS * 86400000;
    } catch (e) {
      // blocked storage: the banner shows
    }
    return {
      hidden,
      artworkFailed: false,
      switching: false,
      switchedOff: false
    };
  },
  computed: {
    monogram() {
      var _this$promo2;
      return (((_this$promo2 = this.promo) === null || _this$promo2 === void 0 ? void 0 : _this$promo2.name) || '?').trim().charAt(0).toUpperCase();
    },
    price() {
      var _this$promo3;
      const from = (_this$promo3 = this.promo) === null || _this$promo3 === void 0 ? void 0 : _this$promo3.priceFrom;
      return from ? Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_PriceFrom', amount(from.amount_eur), intervalLabel(from.interval)) : '';
    },
    screenUrl() {
      var _this$promo4;
      const slug = encodeURIComponent(((_this$promo4 = this.promo) === null || _this$promo4 === void 0 ? void 0 : _this$promo4.slug) || '');
      return `index.php?module=FeinwerkMarketplaceConnector&action=index&fwmc_open=${slug}`;
    },
    settingsUrl() {
      return 'index.php?module=CoreAdminHome&action=generalSettings#/FeinwerkMarketplaceConnector';
    }
  },
  methods: {
    proxied: proxied,
    tr(key) {
      return Object(external_CoreHome_["translate"])(`FeinwerkMarketplaceConnector_${key}`);
    },
    // One click switches all recommendations off (the setting "Show recommendations"); General
    // settings switch them on again. If that fails, the settings page opens instead.
    async switchOff() {
      var _this$promo5;
      const url = ((_this$promo5 = this.promo) === null || _this$promo5 === void 0 ? void 0 : _this$promo5.offUrl) || '';
      if (this.switching || !url) {
        return;
      }
      this.switching = true;
      try {
        var _this$promo6;
        const response = await fetch(url, {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            Accept: 'application/json'
          },
          body: new URLSearchParams({
            nonce: ((_this$promo6 = this.promo) === null || _this$promo6 === void 0 ? void 0 : _this$promo6.offNonce) || ''
          })
        });
        const result = await response.json();
        if (result && result.off) {
          this.switchedOff = true;
          return;
        }
      } catch (e) {
        // no answer of ours: fall back to the settings page
      }
      window.location.href = this.settingsUrl;
    },
    dismiss() {
      this.hidden = true;
      try {
        var _this$promo7;
        window.localStorage.setItem(storageKey(((_this$promo7 = this.promo) === null || _this$promo7 === void 0 ? void 0 : _this$promo7.slug) || ''), String(Date.now()));
      } catch (e) {
        // blocked storage: hidden until the page reloads
      }
    }
  }
}));
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/PromoBanner/PromoBanner.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/PromoBanner/PromoBanner.vue



PromoBannervue_type_script_lang_ts.render = PromoBannervue_type_template_id_4150a562_render

/* harmony default export */ var PromoBanner = (PromoBannervue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/DetailModal/DetailModal.vue?vue&type=template&id=5eacd6f9

const DetailModalvue_type_template_id_5eacd6f9_hoisted_1 = {
  class: "fwmc fwmc-modal uiControl"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_2 = {
  key: 0,
  class: "loadingPiwik"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_3 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("img", {
  src: "plugins/Morpheus/images/loading-blue.gif",
  alt: ""
}, null, -1);
const DetailModalvue_type_template_id_5eacd6f9_hoisted_4 = {
  key: 1,
  class: "alert alert-warning"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_5 = {
  key: 0,
  class: "fwmc-note"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_6 = {
  class: "fwmc-modal-head"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_7 = {
  class: "fwmc-card-media fwmc-card-media--modal"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_8 = ["src", "alt"];
const DetailModalvue_type_template_id_5eacd6f9_hoisted_9 = {
  key: 1,
  class: "fwmc-avatar fwmc-avatar--lg"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_10 = {
  key: 0,
  class: "fwmc-sub"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_11 = {
  class: "fwmc-meta"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_12 = {
  key: 0,
  class: "fwmc-badge"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_13 = {
  class: "fwmc-cta fwmc-cta--top"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_14 = ["href"];
const DetailModalvue_type_template_id_5eacd6f9_hoisted_15 = ["title"];
const DetailModalvue_type_template_id_5eacd6f9_hoisted_16 = ["title"];
const DetailModalvue_type_template_id_5eacd6f9_hoisted_17 = ["href", "title"];
const DetailModalvue_type_template_id_5eacd6f9_hoisted_18 = {
  key: 7,
  class: "fwmc-badge fwmc-status--installed"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_19 = {
  key: 8,
  class: "fwmc-meta"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_20 = {
  class: "fwmc-modal-columns"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_21 = {
  class: "fwmc-modal-main"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_22 = ["innerHTML"];
const DetailModalvue_type_template_id_5eacd6f9_hoisted_23 = {
  class: "fwmc-shots"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_24 = ["onClick"];
const DetailModalvue_type_template_id_5eacd6f9_hoisted_25 = ["src", "alt"];
const DetailModalvue_type_template_id_5eacd6f9_hoisted_26 = {
  class: "fwmc-modal-side"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_27 = {
  class: "fwmc-features"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_28 = ["href"];
const DetailModalvue_type_template_id_5eacd6f9_hoisted_29 = {
  class: "fwmc-plan-name"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_30 = {
  class: "fwmc-plan-price"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_31 = {
  key: 0,
  class: "fwmc-meta"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_32 = {
  key: 1,
  class: "fwmc-meta"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_33 = {
  class: "fwmc-plan-name"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_34 = {
  class: "fwmc-plan-price"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_35 = {
  key: 0,
  class: "fwmc-meta"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_36 = {
  key: 1,
  class: "fwmc-meta"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_37 = {
  class: "fwmc-license-state"
};
const DetailModalvue_type_template_id_5eacd6f9_hoisted_38 = {
  key: 0,
  class: "fwmc-badge fwmc-badge--license-ok"
};
const _hoisted_39 = {
  key: 1,
  class: "fwmc-badge fwmc-badge--license-missing"
};
const _hoisted_40 = {
  class: "fwmc-modal-foot"
};
const _hoisted_41 = {
  key: 0,
  class: "fwmc-versions"
};
const _hoisted_42 = {
  class: "fwmc-tablewrap"
};
const _hoisted_43 = {
  class: "fwmc-table entityTable"
};
const _hoisted_44 = {
  key: 0,
  class: "fwmc-badge fwmc-badge--dbwarn"
};
const _hoisted_45 = {
  class: "fwmc-cta"
};
const _hoisted_46 = ["href"];
const _hoisted_47 = ["title"];
const _hoisted_48 = ["title"];
const _hoisted_49 = ["href", "title"];
const _hoisted_50 = {
  key: 7,
  class: "fwmc-badge fwmc-status--installed"
};
const _hoisted_51 = {
  key: 8,
  class: "fwmc-meta"
};
const _hoisted_52 = ["aria-label"];
const _hoisted_53 = ["src", "alt"];
function DetailModalvue_type_template_id_5eacd6f9_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_InstallForm = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("InstallForm");
  const _component_LicenseActions = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("LicenseActions");
  const _component_BugReportForm = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("BugReportForm");
  const _component_MatomoDialog = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("MatomoDialog");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_MatomoDialog, {
    modelValue: _ctx.opened,
    "onUpdate:modelValue": _cache[4] || (_cache[4] = $event => _ctx.opened = $event),
    onClose: _cache[5] || (_cache[5] = $event => _ctx.$emit('close'))
  }, {
    default: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withCtx"])(() => [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_1, [_ctx.loading ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_2, [DetailModalvue_type_template_id_5eacd6f9_hoisted_3, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" Loading… ")])) : _ctx.errorMessage ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_4, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DetailError')) + " " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.errorMessage), 1)) : _ctx.detail ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 2
    }, [_ctx.stale ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_5, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate(_ctx.errorKind === 'auth' ? 'FeinwerkMarketplaceConnector_AuthErrorNote' : 'FeinwerkMarketplaceConnector_CachedNote')), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_6, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_7, [_ctx.detail.artwork_url && !_ctx.artworkFailed ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("img", {
      key: 0,
      src: _ctx.proxied(_ctx.detail.artwork_url),
      alt: _ctx.detail.display_name,
      onError: _cache[0] || (_cache[0] = $event => _ctx.artworkFailed = true)
    }, null, 40, DetailModalvue_type_template_id_5eacd6f9_hoisted_8)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", DetailModalvue_type_template_id_5eacd6f9_hoisted_9, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.monogram), 1))]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h2", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.detail.display_name), 1), _ctx.detail.description ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", DetailModalvue_type_template_id_5eacd6f9_hoisted_10, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.detail.description), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", DetailModalvue_type_template_id_5eacd6f9_hoisted_11, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", {
      class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])(["fwmc-badge", 'fwmc-badge--' + _ctx.detail.tier])
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.detail.tier), 3), _ctx.detail.category ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", DetailModalvue_type_template_id_5eacd6f9_hoisted_12, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.detail.category), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.latestVersionInfo ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 1
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" · v" + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.latestVersionInfo.version) + " ", 1), _ctx.latestVersionInfo.matomo_min ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 0
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" · Matomo ≥ " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.latestVersionInfo.matomo_min), 1)], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.latestVersionInfo.php_min ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 1
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" · PHP ≥ " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.latestVersionInfo.php_min), 1)], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_13, [_ctx.cta === 'install' || _ctx.cta === 'update' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_InstallForm, {
      key: 0,
      "action-url": _ctx.actionUrl,
      nonce: _ctx.nonce,
      slug: _ctx.slug,
      version: _ctx.row.latestVersion,
      label: _ctx.cta === 'install' ? _ctx.translate('FeinwerkMarketplaceConnector_Install') : _ctx.translate('FeinwerkMarketplaceConnector_UpdateTo', _ctx.row.latestVersion),
      "plugin-name": _ctx.row.displayName,
      "is-update": _ctx.cta === 'update',
      "db-change": _ctx.row.dbChange || null,
      held: _ctx.cta === 'update' && _ctx.targetHasDbWarning
    }, null, 8, ["action-url", "nonce", "slug", "version", "label", "plugin-name", "is-update", "db-change", "held"])) : _ctx.cta === 'claim' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_LicenseActions, {
      key: 1,
      kind: "claim",
      url: _ctx.claimUrl,
      nonce: _ctx.nonce,
      slug: _ctx.slug
    }, null, 8, ["url", "nonce", "slug"])) : _ctx.cta === 'buyhere' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_LicenseActions, {
      key: 2,
      kind: "buy",
      url: _ctx.buyUrl,
      nonce: _ctx.nonce,
      slug: _ctx.slug
    }, null, 8, ["url", "nonce", "slug"])) : _ctx.cta === 'buy' && _ctx.safeStorefrontUrl ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
      key: 3,
      href: _ctx.safeStorefrontUrl,
      target: "_blank",
      rel: "noreferrer noopener",
      class: "fwmc-buy-link"
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_BuyOnMarketplace')) + " ↗ ", 9, DetailModalvue_type_template_id_5eacd6f9_hoisted_14)) : _ctx.cta === 'dev' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", {
      key: 4,
      class: "fwmc-badge fwmc-status--not_installed",
      title: _ctx.translate('FeinwerkMarketplaceConnector_DevCheckoutHint')
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DevCheckout')), 9, DetailModalvue_type_template_id_5eacd6f9_hoisted_15)) : _ctx.cta === 'switching' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", {
      key: 5,
      class: "fwmc-badge fwmc-status--not_installed",
      title: _ctx.translate('FeinwerkMarketplaceConnector_SwitchingHint')
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_Switching')), 9, DetailModalvue_type_template_id_5eacd6f9_hoisted_16)) : _ctx.cta === 'activate' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
      key: 6,
      class: "btn",
      href: _ctx.activateHref,
      title: _ctx.translate('FeinwerkMarketplaceConnector_StatusInactiveHint')
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_Activate')), 9, DetailModalvue_type_template_id_5eacd6f9_hoisted_17)) : _ctx.cta === 'current' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", DetailModalvue_type_template_id_5eacd6f9_hoisted_18, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_StatusCurrent')), 1)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", DetailModalvue_type_template_id_5eacd6f9_hoisted_19, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_NoVersionYet')), 1))])])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_20, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_21, [_ctx.detail.listing && _ctx.detail.listing.long_description_html ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", {
      key: 0,
      innerHTML: _ctx.detail.listing.long_description_html
    }, null, 8, DetailModalvue_type_template_id_5eacd6f9_hoisted_22)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.screenshots.length ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 1
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h3", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_Screenshots')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_23, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.screenshots, (shot, i) => {
      return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("button", {
        key: shot,
        type: "button",
        class: "fwmc-shot",
        onClick: $event => _ctx.lightbox = shot
      }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("img", {
        src: _ctx.proxied(shot),
        onError: _cache[1] || (_cache[1] = (...args) => _ctx.hideImage && _ctx.hideImage(...args)),
        alt: `${_ctx.translate('FeinwerkMarketplaceConnector_Screenshots')} ${i + 1}`
      }, null, 40, DetailModalvue_type_template_id_5eacd6f9_hoisted_25)], 8, DetailModalvue_type_template_id_5eacd6f9_hoisted_24);
    }), 128))])], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_26, [_ctx.features.length ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 0
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h3", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_Features')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("ul", DetailModalvue_type_template_id_5eacd6f9_hoisted_27, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.features, f => {
      return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("li", {
        key: f
      }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(f), 1);
    }), 128))])], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.detail.plans && _ctx.detail.plans.length ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 1
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h3", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_Plans')), 1), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.detail.plans, plan => {
      return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
        key: plan.name_en
      }, [_ctx.safeStorefrontUrl ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
        key: 0,
        href: _ctx.safeStorefrontUrl,
        target: "_blank",
        rel: "noreferrer noopener",
        class: "fwmc-plan-link"
      }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", {
        class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])(["fwmc-plan", {
          'fwmc-plan--highlight': plan.highlight
        }])
      }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_29, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.planName(plan)), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_30, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.amount(plan.price_eur)) + " € / " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.intervalLabel(plan.interval)), 1), plan.max_users ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_31, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_MaxUsers', plan.max_users)), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.planNote(plan) ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_32, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.planNote(plan)), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)], 2)], 8, DetailModalvue_type_template_id_5eacd6f9_hoisted_28)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", {
        key: 1,
        class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])(["fwmc-plan", {
          'fwmc-plan--highlight': plan.highlight
        }])
      }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_33, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.planName(plan)), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_34, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.amount(plan.price_eur)) + " € / " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.intervalLabel(plan.interval)), 1), plan.max_users ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_35, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_MaxUsers', plan.max_users)), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.planNote(plan) ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_36, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.planNote(plan)), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)], 2))], 64);
    }), 128))], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DetailModalvue_type_template_id_5eacd6f9_hoisted_37, [_ctx.row && _ctx.row.license ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", DetailModalvue_type_template_id_5eacd6f9_hoisted_38, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_LicenseActive')) + " ", 1), _ctx.row.license.valid_until ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 0
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" · " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_LicenseUntil', _ctx.row.license.valid_until)), 1)], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])) : _ctx.row && _ctx.row.needsLicense ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", _hoisted_39, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_NoLicense')), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_40, [_ctx.detail.versions && _ctx.detail.versions.length ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("details", _hoisted_41, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("summary", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_Versions')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_42, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("table", _hoisted_43, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("tbody", null, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.detail.versions, v => {
      return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("tr", {
        key: v.version
      }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, "v" + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(v.version), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])((v.released_at || '').slice(0, 10)), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(v.channel), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, [v.matomo_min ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
        key: 0
      }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])("Matomo ≥ " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(v.matomo_min), 1)], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), v.php_min ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
        key: 1
      }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" · PHP ≥ " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(v.php_min), 1)], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, [v.db_warning ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", _hoisted_44, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DbWarning')), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])]);
    }), 128))])])])])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_45, [_ctx.cta === 'install' || _ctx.cta === 'update' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_InstallForm, {
      key: 0,
      "action-url": _ctx.actionUrl,
      nonce: _ctx.nonce,
      slug: _ctx.slug,
      version: _ctx.row.latestVersion,
      label: _ctx.cta === 'install' ? _ctx.translate('FeinwerkMarketplaceConnector_Install') : _ctx.translate('FeinwerkMarketplaceConnector_UpdateTo', _ctx.row.latestVersion),
      "plugin-name": _ctx.row.displayName,
      "is-update": _ctx.cta === 'update',
      "db-change": _ctx.row.dbChange || null,
      held: _ctx.cta === 'update' && _ctx.targetHasDbWarning
    }, null, 8, ["action-url", "nonce", "slug", "version", "label", "plugin-name", "is-update", "db-change", "held"])) : _ctx.cta === 'claim' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_LicenseActions, {
      key: 1,
      kind: "claim",
      url: _ctx.claimUrl,
      nonce: _ctx.nonce,
      slug: _ctx.slug
    }, null, 8, ["url", "nonce", "slug"])) : _ctx.cta === 'buyhere' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_LicenseActions, {
      key: 2,
      kind: "buy",
      url: _ctx.buyUrl,
      nonce: _ctx.nonce,
      slug: _ctx.slug
    }, null, 8, ["url", "nonce", "slug"])) : _ctx.cta === 'buy' && _ctx.safeStorefrontUrl ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
      key: 3,
      href: _ctx.safeStorefrontUrl,
      target: "_blank",
      rel: "noreferrer noopener",
      class: "fwmc-buy-link"
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_BuyOnMarketplace')) + " ↗ ", 9, _hoisted_46)) : _ctx.cta === 'dev' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", {
      key: 4,
      class: "fwmc-badge fwmc-status--not_installed",
      title: _ctx.translate('FeinwerkMarketplaceConnector_DevCheckoutHint')
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_DevCheckout')), 9, _hoisted_47)) : _ctx.cta === 'switching' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", {
      key: 5,
      class: "fwmc-badge fwmc-status--not_installed",
      title: _ctx.translate('FeinwerkMarketplaceConnector_SwitchingHint')
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_Switching')), 9, _hoisted_48)) : _ctx.cta === 'activate' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
      key: 6,
      class: "btn",
      href: _ctx.activateHref,
      title: _ctx.translate('FeinwerkMarketplaceConnector_StatusInactiveHint')
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_Activate')), 9, _hoisted_49)) : _ctx.cta === 'current' ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", _hoisted_50, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_StatusCurrent')), 1)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", _hoisted_51, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_NoVersionYet')), 1))]), _ctx.slug ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_BugReportForm, {
      key: 1,
      slug: _ctx.slug,
      nonce: _ctx.nonce
    }, null, 8, ["slug", "nonce"])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Teleport"], {
      to: "body"
    }, [_ctx.lightbox ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", {
      key: 0,
      class: "fwmc fwmc-lightbox",
      ref: "lightboxEl",
      onClick: _cache[2] || (_cache[2] = $event => _ctx.lightbox = null),
      onKeydown: _cache[3] || (_cache[3] = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withKeys"])($event => _ctx.lightbox = null, ["enter"])),
      role: "button",
      tabindex: "0",
      "aria-label": _ctx.translate('FeinwerkMarketplaceConnector_CloseImage')
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("img", {
      src: _ctx.proxied(_ctx.lightbox),
      alt: _ctx.translate('FeinwerkMarketplaceConnector_Screenshots')
    }, null, 8, _hoisted_53)], 40, _hoisted_52)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]))], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])]),
    _: 1
  }, 8, ["modelValue"]);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/DetailModal/DetailModal.vue?vue&type=template&id=5eacd6f9

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/BugReportForm/BugReportForm.vue?vue&type=template&id=2e33c3f4

const BugReportFormvue_type_template_id_2e33c3f4_hoisted_1 = {
  class: "fwmc-bugreport"
};
const BugReportFormvue_type_template_id_2e33c3f4_hoisted_2 = ["disabled"];
const BugReportFormvue_type_template_id_2e33c3f4_hoisted_3 = ["disabled"];
const BugReportFormvue_type_template_id_2e33c3f4_hoisted_4 = ["disabled"];
const BugReportFormvue_type_template_id_2e33c3f4_hoisted_5 = {
  key: 0,
  class: "fwmc-bugreport-files"
};
const BugReportFormvue_type_template_id_2e33c3f4_hoisted_6 = {
  key: 1,
  class: "alert alert-success"
};
const BugReportFormvue_type_template_id_2e33c3f4_hoisted_7 = {
  key: 2,
  class: "alert alert-warning"
};
const BugReportFormvue_type_template_id_2e33c3f4_hoisted_8 = ["disabled"];
function BugReportFormvue_type_template_id_2e33c3f4_render(_ctx, _cache, $props, $setup, $data, $options) {
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", BugReportFormvue_type_template_id_2e33c3f4_hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("a", {
    href: "#",
    onClick: _cache[0] || (_cache[0] = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])($event => _ctx.open = !_ctx.open, ["prevent"])),
    class: "fwmc-bugreport-toggle"
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ReportBug')), 1), _ctx.open ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("form", {
    key: 0,
    class: "fwmc-bugreport-form",
    onSubmit: _cache[4] || (_cache[4] = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])((...args) => _ctx.submit && _ctx.submit(...args), ["prevent"]))
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("label", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ReportBugTitle')) + " ", 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "text",
    "onUpdate:modelValue": _cache[1] || (_cache[1] = $event => _ctx.title = $event),
    maxlength: "120",
    disabled: _ctx.sending
  }, null, 8, BugReportFormvue_type_template_id_2e33c3f4_hoisted_2), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelText"], _ctx.title, void 0, {
    trim: true
  }]])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("label", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ReportBugDescription')) + " ", 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("textarea", {
    "onUpdate:modelValue": _cache[2] || (_cache[2] = $event => _ctx.description = $event),
    rows: "4",
    maxlength: "5000",
    disabled: _ctx.sending
  }, null, 8, BugReportFormvue_type_template_id_2e33c3f4_hoisted_3), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelText"], _ctx.description, void 0, {
    trim: true
  }]])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("label", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('FeinwerkMarketplaceConnector_ReportBugScreenshots')) + " ", 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    type: "file",
    accept: "image/png,image/jpeg,image/webp",
    multiple: "",
    disabled: _ctx.sending,
    onChange: _cache[3] || (_cache[3] = (...args) => _ctx.pickFiles && _ctx.pickFiles(...args))
  }, null, 40, BugReportFormvue_type_template_id_2e33c3f4_hoisted_4)]), _ctx.files.length ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("ul", BugReportFormvue_type_template_id_2e33c3f4_hoisted_5, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.files, f => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("li", {
      key: f.name
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(f.name) + " (" + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(Math.round(f.size / 1024)) + " KB)", 1);
  }), 128))])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.successMessage ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", BugReportFormvue_type_template_id_2e33c3f4_hoisted_6, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.successMessage), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.errorMessage ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", BugReportFormvue_type_template_id_2e33c3f4_hoisted_7, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.errorMessage), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "submit",
    class: "btn",
    disabled: _ctx.sending
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate(_ctx.sending ? 'FeinwerkMarketplaceConnector_ReportBugSending' : 'FeinwerkMarketplaceConnector_ReportBugSubmit')), 9, BugReportFormvue_type_template_id_2e33c3f4_hoisted_8)], 32)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/BugReportForm/BugReportForm.vue?vue&type=template&id=2e33c3f4

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/BugReportForm/BugReportForm.vue?vue&type=script&lang=ts


const MAX_FILES = 3;
const MAX_BYTES = 4 * 1024 * 1024;
const TYPES = ['image/png', 'image/jpeg', 'image/webp'];
/* harmony default export */ var BugReportFormvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  props: {
    slug: {
      type: String,
      required: true
    },
    nonce: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      open: false,
      title: '',
      description: '',
      files: [],
      sending: false,
      successMessage: null,
      errorMessage: null
    };
  },
  methods: {
    translate: external_CoreHome_["translate"],
    pickFiles(event) {
      this.errorMessage = null;
      const list = Array.from(event.target.files || []);
      if (list.length > MAX_FILES) {
        this.errorMessage = Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_ReportBugTooMany');
        this.files = [];
        return;
      }
      // The first file that does not fit, in the order picked: wrong type, else too large.
      const bad = list.find(f => !TYPES.includes(f.type) || f.size > MAX_BYTES);
      if (bad) {
        this.errorMessage = Object(external_CoreHome_["translate"])(TYPES.includes(bad.type) ? 'FeinwerkMarketplaceConnector_ReportBugFileTooLarge' : 'FeinwerkMarketplaceConnector_ReportBugFileWrongType', bad.name);
        this.files = [];
        return;
      }
      this.files = list;
    },
    async post(action, fields) {
      const body = new URLSearchParams(Object.assign({
        nonce: this.nonce
      }, fields));
      const response = await fetch(`index.php?module=FeinwerkMarketplaceConnector&action=${action}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          Accept: 'application/json'
        },
        body
      });
      return response.json();
    },
    fileToBase64(file) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result.split(',', 2)[1] || '');
        reader.onerror = reject;
        reader.readAsDataURL(file);
      });
    },
    async submit() {
      this.successMessage = null;
      this.errorMessage = null;
      if (!this.title || !this.description) {
        this.errorMessage = Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_ReportBugRequired');
        return;
      }
      this.sending = true;
      try {
        const created = await this.post('reportBug', {
          slug: this.slug,
          title: this.title,
          description: this.description
        });
        if (!created.ok) {
          this.errorMessage = Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_ReportBugFailed', created.error || '');
          return;
        }
        const failed = [];
        // One screenshot per request, one after the other: no request bursts the PHP body limits.
        for (let i = 0; i < this.files.length; i += 1) {
          const f = this.files[i];
          // eslint-disable-next-line no-await-in-loop
          const b64 = await this.fileToBase64(f);
          // eslint-disable-next-line no-await-in-loop
          const attached = await this.post('reportBugScreenshot', {
            id: String(created.id),
            image_base64: b64,
            mime: f.type
          });
          if (!attached.ok) failed.push(f.name);
        }
        if (failed.length) {
          this.errorMessage = Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_ReportBugAttachFailed', failed.join(', '));
        } else {
          this.successMessage = Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_ReportBugSuccess');
          this.title = '';
          this.description = '';
          this.files = [];
        }
      } catch (e) {
        this.errorMessage = Object(external_CoreHome_["translate"])('FeinwerkMarketplaceConnector_ReportBugFailed', String(e));
      } finally {
        this.sending = false;
      }
    }
  }
}));
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/BugReportForm/BugReportForm.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/BugReportForm/BugReportForm.vue



BugReportFormvue_type_script_lang_ts.render = BugReportFormvue_type_template_id_2e33c3f4_render

/* harmony default export */ var BugReportForm = (BugReportFormvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/DetailModal/DetailModal.vue?vue&type=script&lang=ts









/* harmony default export */ var DetailModalvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  components: {
    MatomoDialog: external_CoreHome_["MatomoDialog"],
    BugReportForm: BugReportForm,
    InstallForm: InstallForm,
    LicenseActions: LicenseActions
  },
  // Forms in this window tell the server to reopen it after their action (actionContext.ts).
  provide: {
    fwmcInWindow: true
  },
  emits: ['close'],
  props: {
    slug: {
      type: String,
      default: null
    },
    row: {
      type: Object,
      default: null
    },
    nonce: {
      type: String,
      required: true
    },
    // Base URLs for the install/update actions, built server-side with linkTo()
    // and passed down from PluginsPage — mirrors ManageTable's pattern so slug/version
    // travel as hidden POST fields instead of a hand-built index.php query string.
    installUrl: {
      type: String,
      required: true
    },
    updateUrl: {
      type: String,
      required: true
    },
    // Matomo's own activate link (PluginsPage), '' when Matomo does not allow it.
    activateUrl: {
      type: String,
      default: ''
    },
    claimUrl: {
      type: String,
      default: ''
    },
    buyUrl: {
      type: String,
      default: ''
    },
    licensingAvailable: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      opened: false,
      loading: false,
      detail: null,
      stale: false,
      errorKind: null,
      errorMessage: null,
      lightbox: null,
      artworkFailed: false,
      requestSeq: 0
    };
  },
  watch: {
    slug(newSlug) {
      if (newSlug) this.open(newSlug);else this.opened = false;
    },
    lightbox(src) {
      // Materialize closes the whole modal on Escape via a bubbling document listener;
      // a capture-phase listener lets Escape close just the lightbox first.
      if (src) {
        window.addEventListener('keydown', this.onLightboxKeydown, true);
        this.$nextTick(() => {
          var _this$$refs$lightboxE;
          return (_this$$refs$lightboxE = this.$refs.lightboxEl) === null || _this$$refs$lightboxE === void 0 ? void 0 : _this$$refs$lightboxE.focus();
        });
      } else {
        window.removeEventListener('keydown', this.onLightboxKeydown, true);
      }
    }
  },
  beforeUnmount() {
    window.removeEventListener('keydown', this.onLightboxKeydown, true);
  },
  computed: {
    monogram() {
      var _this$detail;
      return (((_this$detail = this.detail) === null || _this$detail === void 0 ? void 0 : _this$detail.display_name) || '?').trim().charAt(0).toUpperCase();
    },
    features() {
      var _this$detail2;
      return ((_this$detail2 = this.detail) === null || _this$detail2 === void 0 || (_this$detail2 = _this$detail2.listing) === null || _this$detail2 === void 0 ? void 0 : _this$detail2.features) || [];
    },
    screenshots() {
      var _this$detail3;
      return ((_this$detail3 = this.detail) === null || _this$detail3 === void 0 || (_this$detail3 = _this$detail3.listing) === null || _this$detail3 === void 0 ? void 0 : _this$detail3.screenshots) || [];
    },
    latestVersionInfo() {
      var _this$detail4;
      return (((_this$detail4 = this.detail) === null || _this$detail4 === void 0 ? void 0 : _this$detail4.versions) || [])[0] || null;
    },
    targetHasDbWarning() {
      var _this$detail5;
      const target = (((_this$detail5 = this.detail) === null || _this$detail5 === void 0 ? void 0 : _this$detail5.versions) || []).find(v => {
        var _this$row;
        return v.version === ((_this$row = this.row) === null || _this$row === void 0 ? void 0 : _this$row.latestVersion);
      });
      return !!(target !== null && target !== void 0 && target.db_warning);
    },
    cta() {
      const {
        row
      } = this;
      if (!row || !row.latestVersion) return 'none';
      if (row.devCheckout && row.status !== 'not_installed') return row.status === 'update_available' ? 'dev' : 'current';
      if (row.switching) return 'switching';
      if (this.licensingAvailable && row.licenseClaimable) return 'claim';
      if (row.needsLicense && !row.license) return this.licensingAvailable ? 'buyhere' : 'buy';
      if (row.status === 'not_installed') return 'install';
      if (row.status === 'update_available') return 'update';
      if (row.inactive && this.activateUrl) return 'activate';
      return 'current';
    },
    activateHref() {
      return activateLink(this.activateUrl, this.slug || '');
    },
    actionUrl() {
      return this.cta === 'update' ? this.updateUrl : this.installUrl;
    },
    // Marketplace-controlled URL: only bind it as an href when it's http(s), so a
    // crafted storefront_url can't smuggle a javascript: URI into the DOM.
    safeStorefrontUrl() {
      var _this$detail6;
      const u = ((_this$detail6 = this.detail) === null || _this$detail6 === void 0 ? void 0 : _this$detail6.storefront_url) || '';
      return /^https?:\/\//i.test(u) ? u : null;
    }
  },
  methods: {
    translate: external_CoreHome_["translate"],
    async open(slug) {
      this.opened = true;
      this.loading = true;
      this.errorMessage = null;
      this.detail = null;
      this.artworkFailed = false;
      this.lightbox = null;
      // Guard against out-of-order responses when plugins are opened in quick
      // succession: only the newest request may write the state.
      this.requestSeq += 1;
      const seq = this.requestSeq;
      // The server answers from its cache within seconds; a hanging connection must not leave the
      // window loading forever.
      const abort = new AbortController();
      const timer = window.setTimeout(() => abort.abort(), 20000);
      try {
        const response = await fetch(`index.php?module=FeinwerkMarketplaceConnector&action=detail&slug=${encodeURIComponent(slug)}`, {
          credentials: 'same-origin',
          headers: {
            Accept: 'application/json'
          },
          signal: abort.signal
        });
        const payload = await response.json();
        if (seq !== this.requestSeq) return;
        if (payload.data) {
          this.detail = payload.data;
          this.stale = !!payload.stale;
          this.errorKind = payload.errorKind || null;
        } else {
          this.errorMessage = payload.error || '';
        }
      } catch (e) {
        if (seq !== this.requestSeq) return;
        this.errorMessage = String(e);
      } finally {
        window.clearTimeout(timer);
        if (seq === this.requestSeq) this.loading = false;
      }
    },
    planName(plan) {
      return external_CoreHome_["Matomo"].language === 'de' && plan.name_de ? plan.name_de : plan.name_en;
    },
    planNote(plan) {
      return external_CoreHome_["Matomo"].language === 'de' && plan.note_de ? plan.note_de : plan.note_en;
    },
    intervalLabel: intervalLabel,
    amount: amount,
    proxied: proxied,
    onLightboxKeydown(event) {
      if (event.key === 'Escape' && this.lightbox) {
        event.preventDefault();
        event.stopImmediatePropagation();
        this.lightbox = null;
      }
    },
    hideImage(event) {
      // A screenshot goes with its button, any other image on its own.
      const image = event.target;
      (image.closest('.fwmc-shot') || image).style.display = 'none';
    }
  }
}));
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/DetailModal/DetailModal.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/DetailModal/DetailModal.vue



DetailModalvue_type_script_lang_ts.render = DetailModalvue_type_template_id_5eacd6f9_render

/* harmony default export */ var DetailModal = (DetailModalvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/FeedbackBar/FeedbackBar.vue?vue&type=template&id=7496ef14

const FeedbackBarvue_type_template_id_7496ef14_hoisted_1 = {
  class: "fwmc-feedback-question"
};
const FeedbackBarvue_type_template_id_7496ef14_hoisted_2 = ["title", "aria-label", "aria-pressed", "onClick"];
const FeedbackBarvue_type_template_id_7496ef14_hoisted_3 = ["d"];
const FeedbackBarvue_type_template_id_7496ef14_hoisted_4 = {
  class: "fwmc-feedback-thanks",
  role: "status"
};
const FeedbackBarvue_type_template_id_7496ef14_hoisted_5 = ["href", "title"];
function FeedbackBarvue_type_template_id_7496ef14_render(_ctx, _cache, $props, $setup, $data, $options) {
  return _ctx.feedback.enabled || _ctx.feedback.supportUrl ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", {
    key: 0,
    class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])(["fwmc-feedback", {
      'is-voted': _ctx.vote !== ''
    }])
  }, [_ctx.feedback.enabled ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
    key: 0
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", FeedbackBarvue_type_template_id_7496ef14_hoisted_1, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.question), 1), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.choices, choice => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("button", {
      key: choice,
      type: "button",
      class: "fwmc-feedback-thumb",
      title: _ctx.label(choice),
      "aria-label": _ctx.label(choice),
      "aria-pressed": _ctx.vote === choice ? 'true' : 'false',
      onClick: $event => _ctx.send(choice)
    }, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("svg", {
      viewBox: "0 0 24 24",
      width: "14",
      height: "14",
      "aria-hidden": "true",
      focusable: "false",
      class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])(`fwmc-feedback-icon-${choice}`)
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("path", {
      d: _ctx.thumbPath
    }, null, 8, FeedbackBarvue_type_template_id_7496ef14_hoisted_3)], 2))], 8, FeedbackBarvue_type_template_id_7496ef14_hoisted_2);
  }), 128)), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", FeedbackBarvue_type_template_id_7496ef14_hoisted_4, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.thanks), 1)], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.feedback.supportUrl ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("a", {
    key: 1,
    class: "fwmc-feedback-support",
    href: _ctx.feedback.supportUrl,
    target: "_blank",
    rel: "noopener noreferrer",
    title: _ctx.tr('SupportTitle')
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.tr('Support')), 9, FeedbackBarvue_type_template_id_7496ef14_hoisted_5)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)], 2)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true);
}
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/FeedbackBar/FeedbackBar.vue?vue&type=template&id=7496ef14

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/FeedbackBar/FeedbackBar.vue?vue&type=script&lang=ts


const PLUGIN = 'FeinwerkMarketplaceConnector';
/** a thumb outline (24 x 24); the down thumb is the same, rotated */
const THUMB_PATH = 'M3 10h4v10H3zM7 10l4-7c1.3 0 2.2 1.1 2 2.4L12.4 9H19a2 2 0 0 1 2 2.3l-1.2 7A2 2 0 0 1 17.8 20H7';
/** localStorage: this browser's vote per question and connector version (a convenience only) */
function FeedbackBarvue_type_script_lang_ts_storageKey(topic) {
  return `fwmc.feedback.${topic}`;
}
function storedVote(topic, version) {
  try {
    const stored = JSON.parse(window.localStorage.getItem(FeedbackBarvue_type_script_lang_ts_storageKey(topic)) || '{}');
    return stored.version === version && (stored.vote === 'up' || stored.vote === 'down') ? stored.vote : '';
  } catch (e) {
    return '';
  }
}
/* harmony default export */ var FeedbackBarvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  props: {
    /** the store "topic": one of Core/FeedbackVote::TOPICS */
    topic: {
      type: String,
      required: true
    },
    /** the question, already translated */
    question: {
      type: String,
      required: true
    },
    nonce: {
      type: String,
      required: true
    },
    feedback: {
      type: Object,
      default: () => ({
        enabled: false,
        supportUrl: '',
        version: ''
      })
    }
  },
  data() {
    return {
      vote: storedVote(this.topic, this.feedback.version)
    };
  },
  computed: {
    choices() {
      return ['up', 'down'];
    },
    thumbPath() {
      return THUMB_PATH;
    },
    thanks() {
      if (!this.vote) {
        return '';
      }
      return this.vote === 'up' ? this.tr('FeedbackThanks') : this.tr('FeedbackThanksDown');
    }
  },
  methods: {
    tr(key) {
      return Object(external_CoreHome_["translate"])(`${PLUGIN}_${key}`);
    },
    label(choice) {
      return choice === 'up' ? this.tr('FeedbackYes') : this.tr('FeedbackNo');
    },
    send(vote) {
      if (this.vote) {
        return;
      }
      this.vote = vote;
      try {
        const stored = {
          version: this.feedback.version,
          vote
        };
        window.localStorage.setItem(FeedbackBarvue_type_script_lang_ts_storageKey(this.topic), JSON.stringify(stored));
      } catch (e) {
        // private mode or blocked storage: the thanks is shown anyway
      }
      // Best effort: the vote is a hint for Feinwerk, a failed request needs no error message.
      const body = new URLSearchParams({
        nonce: this.nonce,
        topic: this.topic,
        vote
      });
      fetch(`index.php?module=${PLUGIN}&action=sendFeedback`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          Accept: 'application/json'
        },
        body
      }).catch(() => undefined);
    }
  }
}));
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/FeedbackBar/FeedbackBar.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/FeedbackBar/FeedbackBar.vue



FeedbackBarvue_type_script_lang_ts.render = FeedbackBarvue_type_template_id_7496ef14_render

/* harmony default export */ var FeedbackBar = (FeedbackBarvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/FeinwerkMarketplaceConnector/vue/src/PluginsPage/PluginsPage.vue?vue&type=script&lang=ts










/* harmony default export */ var PluginsPagevue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  components: {
    ManageTable: ManageTable,
    BrowseGrid: BrowseGrid,
    DetailModal: DetailModal,
    FeedbackBar: FeedbackBar,
    TaskList: TaskList,
    LicenseTable: LicenseTable,
    PromoBanner: PromoBanner
  },
  props: {
    rows: {
      type: Array,
      required: true
    },
    formNonce: {
      type: String,
      required: true
    },
    lastUpdated: String,
    refreshError: String,
    refreshErrorKind: String,
    // The connector's own plugin name: it is listed in the marketplace (so it can update itself)
    // but shown as a banner, not as a card.
    selfSlug: {
      type: String,
      default: 'FeinwerkMarketplaceConnector'
    },
    // Auto-apply updates held back because they change the database (slugs).
    dbHeld: {
      type: Array,
      default: () => []
    },
    // The feedback question under the tabs (Controller::index): switch, support URL, version.
    feedback: {
      type: Object,
      default: () => ({
        enabled: false,
        supportUrl: '',
        version: ''
      })
    },
    // The "Aufgaben" tab (Core/ActionItems) and what its buttons need.
    actionItems: {
      type: Array,
      default: () => []
    },
    consoleCommand: {
      type: String,
      default: ''
    },
    accountUrl: {
      type: String,
      default: ''
    },
    switchNowUrl: {
      type: String,
      default: ''
    },
    // The recommendation banner of the Discover tab (Recommendation, null when switched off).
    promo: {
      type: Object,
      default: null
    },
    // "Update several at once" (BatchUpdate): its own nonce and the two actions.
    batchNonce: {
      type: String,
      default: ''
    },
    batchUpdateUrl: {
      type: String,
      default: ''
    },
    batchFinishUrl: {
      type: String,
      default: ''
    },
    // The menu entries of the section, one per tab.
    tasksUrl: {
      type: String,
      default: ''
    },
    discoverUrl: {
      type: String,
      default: ''
    },
    manageUrl: {
      type: String,
      default: ''
    },
    licensesUrl: {
      type: String,
      default: ''
    },
    error: String,
    // false until an instance token is stored; vue-entry may hand it over as a string.
    connected: {
      type: [Boolean, String],
      default: true
    },
    // Browse without an account: {loaded, fetchedAt, url} (Controller::screen).
    browse: {
      type: [Object, String],
      default: () => ({})
    },
    // Size and update settings of this Matomo for the database-update warning (InstallForm,
    // injected).
    instance: {
      type: Object,
      default: null
    },
    connectUrl: {
      type: String,
      default: ''
    },
    settingsUrl: {
      type: String,
      default: ''
    },
    // Built server-side with linkTo() so they keep the admin page's idSite/period/date
    // params, exactly like the pre-Vue twig forms did — see ManageTable for slug/version.
    refreshUrl: {
      type: String,
      required: true
    },
    installUrl: {
      type: String,
      required: true
    },
    updateUrl: {
      type: String,
      required: true
    },
    // Matomo's own activate link for an installed plugin that is off ('' when not allowed).
    activateUrl: {
      type: String,
      default: ''
    },
    // Claim / buy actions and the licensing switch (the store knows the licensing endpoints),
    // the guide for servers without internet access, a plugin to open after returning from the
    // store.
    licensingAvailable: {
      type: [Boolean, String],
      default: false
    },
    offlineDocsUrl: {
      type: String,
      default: ''
    },
    openSlug: {
      type: String,
      default: ''
    },
    // The tab to open after an action (fwmc_tab, Controller::backToScreen).
    initialTab: {
      type: String,
      default: ''
    },
    claimUrl: {
      type: String,
      default: ''
    },
    buyUrl: {
      type: String,
      default: ''
    },
    // What the page shows and where to ask for the current state (ScreenState, backlog 13).
    screenState: {
      type: String,
      default: ''
    },
    stateUrl: {
      type: String,
      default: ''
    }
  },
  provide() {
    return {
      fwmcInstance: this.instance
    };
  },
  data() {
    return {
      // The server says which tab (one action per tab); index#verwaltung is an old bookmark.
      tab: window.location.hash === `#${TAB_MANAGE}` && (this.initialTab || 'entdecken') === 'entdecken' ? TAB_MANAGE : this.initialTab || 'entdecken',
      detailSlug: null,
      // The header form just sent (refresh, self-update): its button stays off, so a double
      // click sends nothing.
      busyForm: null,
      // The state changed while something was open here: a note offers to load the page again.
      stateChanged: false,
      stateChecking: false,
      stateCheckedAt: Date.now()
    };
  },
  mounted() {
    if (this.openSlug) {
      this.detailSlug = this.openSlug;
    }
    this.tidyUrl();
    window.addEventListener('pageshow', this.onPageShow);
    window.addEventListener('popstate', this.onPopState);
    if (this.screenState && this.stateUrl) {
      // Only when the admin comes back, never on a timer: a timer would keep a forgotten page's
      // session alive past Matomo's idle logout (review 5.1.3).
      window.addEventListener('focus', this.checkState);
      document.addEventListener('visibilitychange', this.checkState);
    }
  },
  beforeUnmount() {
    window.removeEventListener('pageshow', this.onPageShow);
    window.removeEventListener('popstate', this.onPopState);
    window.removeEventListener('focus', this.checkState);
    document.removeEventListener('visibilitychange', this.checkState);
  },
  computed: {
    // One question per tab (Core/FeedbackVote::TOPICS).
    feedbackTopic() {
      const topics = {
        aufgaben: 'tasks',
        entdecken: 'discover',
        verwaltung: 'manage',
        lizenzen: 'licenses'
      };
      return topics[this.tab] || 'discover';
    },
    tabs() {
      const label = key => `FeinwerkMarketplaceConnector_${key}`;
      return [{
        id: 'aufgaben',
        label: label('TabTasks'),
        url: this.tasksUrl
      }, {
        id: 'entdecken',
        label: label('TabDiscover'),
        url: this.discoverUrl
      }, {
        id: TAB_MANAGE,
        label: label('TabManage'),
        url: this.manageUrl
      }, {
        id: 'lizenzen',
        label: label('TabLicenses'),
        url: this.licensesUrl
      }];
    },
    licensingOn() {
      return this.licensingAvailable === true || this.licensingAvailable === 'true';
    },
    // Only an http(s) address is bound as a link.
    safeOfflineDocsUrl() {
      return /^https?:\/\//i.test(this.offlineDocsUrl) ? this.offlineDocsUrl : '';
    },
    browseInfo() {
      const b = typeof this.browse === 'object' && this.browse ? this.browse : {};
      return {
        loaded: !!b.loaded,
        fetchedAt: b.fetchedAt || null,
        url: String(b.url || '')
      };
    },
    isConnected() {
      return this.connected === true || this.connected === 'true' || this.connected === '1';
    },
    browseRows() {
      return this.rows.filter(r => r.slug !== this.selfSlug);
    },
    selfUpdate() {
      const self = this.rows.find(r => r.slug === this.selfSlug);
      return self && self.status === 'update_available' && !self.devCheckout ? self : null;
    },
    detailRow() {
      return this.rows.find(r => r.slug === this.detailSlug) || null;
    }
  },
  methods: {
    translate: external_CoreHome_["translate"],
    // The plugin window gets its own history entry (same address), so the browser's Back button
    // closes it instead of leaving the screen.
    openDetail(slug) {
      this.detailSlug = slug;
      try {
        window.history.pushState(Object.assign(Object.assign({}, window.history.state || {}), {}, {
          fwmcOpen: slug
        }), '');
      } catch (e) {
        // no history API: the window still opens
      }
    },
    // Without a connection the plugin window has no data: the card opens the store page.
    openStorePage(slug) {
      const row = this.rows.find(r => r.slug === slug);
      if (row && typeof row.url === 'string' && /^https?:\/\//.test(row.url)) {
        window.open(row.url, '_blank', 'noopener');
      }
    },
    closeDetail() {
      this.detailSlug = null;
      const {
        state
      } = window.history;
      if (state && state.fwmcOpen) {
        window.history.back();
      }
    },
    onPopState(event) {
      if (this.detailSlug && !(event.state && event.state.fwmcOpen)) {
        this.detailSlug = null;
      }
    },
    onFormSubmit(event, form) {
      if (this.busyForm || !claimSubmit()) {
        event.preventDefault();
        return;
      }
      addActionContext(event.target);
      this.busyForm = form;
    },
    // Back to this page through the browser's back cache: the nonce of a sent form is spent and the
    // rows may be outdated, so load the screen again instead of enabling the buttons
    // (review 1.2.1).
    onPageShow(event) {
      if (event.persisted) {
        window.location.reload();
      }
    },
    // Back on the page (window focus, tab shown again): has what it shows changed,
    // e.g. a database update that ran on the console (backlog 13)? Then load it again, unless
    // something is open here; that gets a note instead.
    async checkState() {
      if (document.visibilityState === 'hidden' || this.stateChecking || this.stateChanged || Date.now() - this.stateCheckedAt < 10000) {
        return;
      }
      this.stateChecking = true;
      this.stateCheckedAt = Date.now();
      try {
        const response = await fetch(this.stateUrl, {
          credentials: 'same-origin',
          cache: 'no-store',
          headers: {
            Accept: 'application/json'
          }
        });
        const result = await response.json();
        const state = result && typeof result.state === 'string' ? result.state : '';
        if (state && state !== this.screenState) {
          if (this.somethingOpen() || !this.mayReload()) {
            this.stateChanged = true;
          } else {
            this.reload();
          }
        }
      } catch (e) {
        // no answer of ours (offline, logged out): the page stays as it is
      } finally {
        this.stateChecking = false;
      }
    },
    somethingOpen() {
      const search = document.querySelector('.fwmc input[type="search"]');
      return !!this.detailSlug || !!this.busyForm || submitted() || !!document.querySelector('.fwmc-dbdialog') || !!(search && search.value.trim());
    },
    // At most one automatic reload every two minutes: a state that differs right after loading
    // must not reload the page over and over.
    mayReload() {
      try {
        const last = Number(window.sessionStorage.getItem('fwmc.stateReloadAt') || 0);
        if (Date.now() - last < 120000) {
          return false;
        }
        window.sessionStorage.setItem('fwmc.stateReloadAt', String(Date.now()));
      } catch (e) {
        // blocked storage: no limit can be kept, so the note offers it instead
        return false;
      }
      return true;
    },
    reload() {
      window.location.reload();
    },
    // After an action the address carries fwmc_open (and old links fwmc_tab): drop them, so a
    // reload does not reopen the window. The tab is the page's own action since 5.1.0.
    tidyUrl() {
      const url = new URL(window.location.href);
      if (!url.searchParams.has('fwmc_tab') && !url.searchParams.has('fwmc_open')) {
        return;
      }
      url.searchParams.delete('fwmc_tab');
      url.searchParams.delete('fwmc_open');
      window.history.replaceState(window.history.state, '', url.toString());
    }
  }
}));
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/PluginsPage/PluginsPage.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/PluginsPage/PluginsPage.vue



PluginsPagevue_type_script_lang_ts.render = render

/* harmony default export */ var PluginsPage = (PluginsPagevue_type_script_lang_ts);
// CONCATENATED MODULE: ./plugins/FeinwerkMarketplaceConnector/vue/src/index.ts


// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/entry-lib-no-default.js




/***/ })

/******/ });
});
//# sourceMappingURL=FeinwerkMarketplaceConnector.umd.js.map