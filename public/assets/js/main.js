(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
'use strict';

var _impression = require('@trustpilot/trustbox-framework-vanilla/modules/impression');

var _impression2 = _interopRequireDefault(_impression);

var _api = require('@trustpilot/trustbox-framework-vanilla/modules/slim/api');

var _utils = require('@trustpilot/trustbox-framework-vanilla/modules/utils');

var _queryString = require('@trustpilot/trustbox-framework-vanilla/modules/queryString');

var _stars = require('@trustpilot/trustbox-framework-vanilla/modules/slim/templates/stars');

var _logo = require('@trustpilot/trustbox-framework-vanilla/modules/slim/templates/logo');

var _dom = require('@trustpilot/trustbox-framework-vanilla/modules/dom');

var _init = require('@trustpilot/trustbox-framework-vanilla/modules/slim/init');

var _init2 = _interopRequireDefault(_init);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

_impression2.default.attachImpressionHandler();

var addUtm = (0, _utils.addUtmParams)('Mini');

var _getQueryString = (0, _queryString.getAsObject)(),
    locale = _getQueryString.locale,
    businessUnitId = _getQueryString.businessunitId,
    _getQueryString$theme = _getQueryString.theme,
    theme = _getQueryString$theme === undefined ? 'light' : _getQueryString$theme,
    location = _getQueryString.location,
    templateId = _getQueryString.templateId,
    fontFamily = _getQueryString.fontFamily,
    textColor = _getQueryString.textColor;

var injectWidgetLinks = function injectWidgetLinks(_ref) {
  var _ref$baseData = _ref.baseData,
      totalNumberOfReviews = _ref$baseData.businessEntity.numberOfReviews.total,
      links = _ref$baseData.links;

  var item = document.getElementById('profile-link');
  var baseUrl = totalNumberOfReviews ? links.profileUrl : links.evaluateUrl;
  item.href = addUtm(baseUrl);
};

var populateNumberOfReviews = function populateNumberOfReviews(_ref2) {
  var locale = _ref2.locale,
      _ref2$baseData = _ref2.baseData,
      _ref2$baseData$busine = _ref2$baseData.businessEntity,
      numberOfReviews = _ref2$baseData$busine.numberOfReviews.total,
      trustScore = _ref2$baseData$busine.trustScore,
      translations = _ref2$baseData.translations;

  var reviewsTextContainer = document.getElementById('translations-reviews');
  var trustscoreContainerId = numberOfReviews ? 'businessEntity-numberOfReviews-total' : 'reviews-summary';
  var trustscoreContainer = document.getElementById(trustscoreContainerId);
  var scoreElem = document.getElementById('trust-score');
  var translationString = numberOfReviews ? (0, _utils.insertNumberSeparator)(numberOfReviews, locale) : translations.noReviews;
  (0, _utils.setHtmlContent)(trustscoreContainer, translationString);
  (0, _utils.setTextContent)(scoreElem, trustScore.toFixed(1));
  (0, _utils.setTextContent)(reviewsTextContainer, translations.reviews);
};

var showWrapper = function showWrapper() {
  var wrapper = document.getElementById('tp-widget-wrapper');
  (0, _dom.removeClass)(wrapper, 'tp-widget-wrapper--placeholder');
};

var applyCustomStyling = function applyCustomStyling() {
  if (fontFamily) {
    (0, _utils.setFont)(fontFamily);
  }
  if (textColor) {
    (0, _utils.setTextColor)(textColor);
  }
};

var translateTitle = function translateTitle(translations) {
  if (translations.trustpilotCustomWidget) {
    var title = document.getElementById('tp-widget-title');
    title.innerHTML = translations.trustpilotCustomWidget;
  }
};

var constructTrustBox = function constructTrustBox(_ref3) {
  var baseData = _ref3.baseData,
      locale = _ref3.locale;

  showWrapper();
  (0, _utils.setHtmlLanguage)(locale);
  if (baseData.translations) translateTitle(baseData.translations);
  populateNumberOfReviews({ baseData: baseData, locale: locale });
  (0, _stars.populateStars)(baseData, 'tp-widget-stars', null, locale);
  (0, _logo.populateLogo)();
  injectWidgetLinks({ baseData: baseData });
  if (baseData.settings.customStylesAllowed) {
    applyCustomStyling();
  }
};

var fetchParams = {
  businessUnitId: businessUnitId,
  locale: locale,
  theme: theme,
  location: location
};

(0, _init2.default)(function () {
  return (0, _api.fetchServiceReviewData)(templateId)(fetchParams, constructTrustBox);
});

},{"@trustpilot/trustbox-framework-vanilla/modules/dom":7,"@trustpilot/trustbox-framework-vanilla/modules/impression":9,"@trustpilot/trustbox-framework-vanilla/modules/queryString":11,"@trustpilot/trustbox-framework-vanilla/modules/slim/api":14,"@trustpilot/trustbox-framework-vanilla/modules/slim/init":19,"@trustpilot/trustbox-framework-vanilla/modules/slim/templates/logo":22,"@trustpilot/trustbox-framework-vanilla/modules/slim/templates/stars":23,"@trustpilot/trustbox-framework-vanilla/modules/utils":26}],7:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.populateElements = /* common-shake removed: exports.hasClass = */ exports.removeClass = exports.addClass = undefined;

var _utils = require('./utils');

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

var hasClass = function hasClass(elem, className) {
  if (elem) {
    var elemClassList = elem.getAttribute('class');
    var classNames = elemClassList ? elemClassList.split(' ') : '';
    return classNames.indexOf(className) !== -1;
  }
  return false;
};

var addClass = function addClass(elem, forAddition) {
  if (elem) {
    var elemClassList = elem.getAttribute('class');
    var classNames = elemClassList ? elemClassList.split(' ') : [];

    if (!hasClass(elem, forAddition)) {
      var newClasses = [].concat(_toConsumableArray(classNames), [forAddition]).join(' ');
      elem.setAttribute('class', newClasses);
    }
  }
};

var removeClass = function removeClass(elem, forRemoval) {
  if (elem) {
    var classNames = elem.className.split(' ');
    elem.className = classNames.filter(function (name) {
      return name !== forRemoval;
    }).join(' ');
  }
};

/**
 * Populates a series of elements with HTML content.
 *
 * For each object in a list, either a given string value is used to populate
 * the given element (including optional substitutions); or, where no string
 * value is provided, remove the given element.
 */
var populateElements = function populateElements(elements) {
  elements.forEach(function (_ref) {
    var element = _ref.element,
        string = _ref.string,
        _ref$substitutions = _ref.substitutions,
        substitutions = _ref$substitutions === undefined ? {} : _ref$substitutions;

    if (string) {
      (0, _utils.setHtmlContent)(element, (0, _utils.makeTranslations)(substitutions, string), false);
    } else {
      (0, _utils.removeElement)(element);
    }
  });
};

exports.addClass = addClass;
exports.removeClass = removeClass;
/* common-shake removed: exports.hasClass = */ void hasClass;
exports.populateElements = populateElements;

},{"./utils":26}],9:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _queryString3 = require('./queryString');

var _utils = require('./utils');

var _rootUri = require('./rootUri');

var _rootUri2 = _interopRequireDefault(_rootUri);

var _xhr = require('./xhr');

var _xhr2 = _interopRequireDefault(_xhr);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _objectWithoutProperties(obj, keys) { var target = {}; for (var i in obj) { if (keys.indexOf(i) >= 0) continue; if (!Object.prototype.hasOwnProperty.call(obj, i)) continue; target[i] = obj[i]; } return target; }

function setCookie(cname, cvalue, expires) {
  var path = 'path=/';
  var domain = 'domain=' + window.location.hostname.replace(/^.*\.([^.]+\.[^.]+)/, '$1');
  var samesite = 'samesite=none';
  var secure = 'secure';
  document.cookie = [cname + '=' + cvalue, path, expires, domain, samesite, secure].join('; ');
  document.cookie = [cname + '-legacy=' + cvalue, path, expires, domain].join('; ');
}

function makeTrackingUrl(eventName, impressionData) {
  // Destructure the impressionData and query params so that we only pass the
  // desired values for constructing the tracking URL.
  var userId = impressionData.anonymousId,
      _ = impressionData.sessionExpiry,
      impressionParams = _objectWithoutProperties(impressionData, ['anonymousId', 'sessionExpiry']);

  var _queryString = (0, _queryString3.getAsObject)(),
      businessUnitId = _queryString.businessunitId,
      widgetId = _queryString.templateId,
      widgetSettings = _objectWithoutProperties(_queryString, ['businessunitId', 'templateId']);

  var urlParams = _extends({}, widgetSettings, impressionParams, widgetSettings.group && userId ? { userId: userId } : { nosettings: 1 }, {
    businessUnitId: businessUnitId,
    widgetId: widgetId
  });
  var urlParamsString = Object.keys(urlParams).map(function (property) {
    return property + '=' + encodeURIComponent(urlParams[property]);
  }).join('&');
  return (0, _rootUri2.default)() + '/stats/' + eventName + '?' + urlParamsString;
}

function setTrackingCookies(eventName, _ref) {
  var session = _ref.session,
      testId = _ref.testId,
      sessionExpiry = _ref.sessionExpiry;

  var _queryString2 = (0, _queryString3.getAsObject)(),
      group = _queryString2.group,
      businessUnitId = _queryString2.businessunitId;

  if (!group) {
    return;
  }

  if (!testId || !session) {
    // eslint-disable-next-line
    console.warn('TrustBox Optimizer test group detected but no running test settings found!');
  }

  if (sessionExpiry) {
    var settings = { group: group, session: session, testId: testId };
    setCookie('TrustboxSplitTest_' + businessUnitId, encodeURIComponent(JSON.stringify(settings)), sessionExpiry);
  }
}

function trackEventRequest(eventName, impressionData) {
  setTrackingCookies(eventName, impressionData);
  var url = makeTrackingUrl(eventName, impressionData);
  try {
    (0, _xhr2.default)({ url: url });
  } catch (e) {
    // do nothing
  }
}

var trackImpression = function trackImpression(data) {
  trackEventRequest('TrustboxImpression', data);
};

var trackView = function trackView(data) {
  trackEventRequest('TrustboxView', data);
};

var trackEngagement = function trackEngagement(data) {
  trackEventRequest('TrustboxEngagement', data);
};

var id = null;

var attachImpressionHandler = function attachImpressionHandler() {
  (0, _utils.addEventListener)(window, 'message', function (event) {
    if (typeof event.data !== 'string') {
      return;
    }

    var e = void 0;
    try {
      e = { data: JSON.parse(event.data) };
    } catch (e) {
      // probably not for us
      return;
    }

    if (e.data.command === 'setId') {
      id = e.data.widgetId;
      window.parent.postMessage(JSON.stringify({ command: 'impression', widgetId: id }), '*');
      return;
    }

    if (e.data.command === 'impression-received') {
      delete e.data.command;
      trackImpression(e.data);
    }

    if (e.data.command === 'trustbox-in-viewport') {
      delete e.data.command;
      trackView(e.data);
    }
  });
};

var tracking = {
  engagement: trackEngagement,
  attachImpressionHandler: attachImpressionHandler
};

exports.default = tracking;

},{"./queryString":11,"./rootUri":12,"./utils":26,"./xhr":27}],11:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.getAsObject = /* common-shake removed: exports.getQueryParams = */ undefined;

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _fn = require('./fn');

/**
 * Convert a parameter string to an object.
 */
function paramsToObject(paramString) {
  var tokens = ['?', '#'];
  var dropFirstIfToken = function dropFirstIfToken(str) {
    return tokens.indexOf(str[0]) !== -1 ? str.substring(1) : str;
  };
  var toPairs = function toPairs(str) {
    return str.split('&').filter(Boolean).map(function (pairString) {
      var _pairString$split = pairString.split('='),
          _pairString$split2 = _slicedToArray(_pairString$split, 2),
          key = _pairString$split2[0],
          value = _pairString$split2[1];

      try {
        var dKey = decodeURIComponent(key);
        var dValue = decodeURIComponent(value);
        return [dKey, dValue];
      } catch (e) {}
    }).filter(Boolean);
  };
  var mkObject = (0, _fn.compose)(_fn.pairsToObject, toPairs, dropFirstIfToken);
  return mkObject(paramString);
}

/**
 * Get all params from the TrustBox's URL.
 *
 * The only query parameters required to run the initial load of a TrustBox are
 * businessUnitId and templateId. The rest are only used within the TrustBox to
 * make the data call(s) and set options. These are passed as part of the hash
 * to ensure that we can properly utilise browser caching.
 *
 * Note: this only captures single occurences of values in the URL.
 *
 * @param {Location} location - A location object for which to get query params.
 * @return {Object} - All query params for the given location.
 */
function getQueryParams() {
  var location = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : window.location;

  var queryParams = paramsToObject(location.search);
  var hashParams = paramsToObject(location.hash);
  return _extends({}, queryParams, hashParams);
}

/* common-shake removed: exports.getQueryParams = */ void getQueryParams;
exports.getAsObject = getQueryParams;

},{"./fn":8}],26:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
/* common-shake removed: exports.sortAttributeRatings = */ exports.showTrustBox = /* common-shake removed: exports.setWidgetAlignment = */ exports.setTextContent = exports.setTextColor = /* common-shake removed: exports.setPopupAlignment = */ exports.setFont = exports.setHtmlLanguage = exports.setHtmlContent = /* common-shake removed: exports.setBorderColor = */ exports.sanitizeHtmlProp = /* common-shake removed: exports.sanitizeHtml = */ exports.sanitizeColor = exports.removeElement = /* common-shake removed: exports.regulateFollowForLocation = */ /* common-shake removed: exports.range = */ exports.makeTranslations = /* common-shake removed: exports.handlePopoverPosition = */ exports.insertNumberSeparator = /* common-shake removed: exports.injectWidgetLinks = */ /* common-shake removed: exports.getTrustpilotBusinessUnitId = */ exports.getOnPageReady = exports.addUtmParams = exports.addEventListener = undefined;

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }(); /* eslint-disable no-console */


var _dom = require('./dom');

var _styleAlignmentPositions = require('./models/styleAlignmentPositions');

var _rootUri = require('./rootUri');

var _rootUri2 = _interopRequireDefault(_rootUri);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function addEventListener(element, type, listener) {
  if (element) {
    if (element.addEventListener) {
      element.addEventListener(type, listener);
    } else {
      element.attachEvent('on' + type, function (e) {
        e = e || window.event;
        e.preventDefault = e.preventDefault || function () {
          e.returnValue = false;
        };
        e.stopPropagation = e.stopPropagation || function () {
          e.cancelBubble = true;
        };
        listener.call(element, e);
      });
    }
  }
}

function getOnPageReady() {
  return new Promise(function (resolve) {
    var resolveWithTimeout = function resolveWithTimeout() {
      setTimeout(function () {
        resolve();
      }, 0);
    };
    if (document.readyState === 'complete') {
      resolveWithTimeout();
    } else {
      addEventListener(window, 'load', function () {
        resolveWithTimeout();
      });
    }
  });
}

function insertNumberSeparator(input, locale) {
  try {
    input.toLocaleString();
  } catch (e) {
    return input;
  }
  return input.toLocaleString(locale || 'en-US');
}

function setTextContent(element, content) {
  if (!element) {
    console.log('Attempting to set content on missing element');
  } else if ('innerText' in element) {
    // IE8
    element.innerText = content;
  } else {
    element.textContent = content;
  }
}

var sanitizeHtmlProp = function sanitizeHtmlProp(string) {
  if (typeof string === 'string') {
    string = string.replaceAll('>', '');
    string = string.replaceAll('<', '');
    string = string.replaceAll('"', '');
  }
  return string;
};

var sanitizeHtml = function sanitizeHtml(string) {
  if (typeof string !== 'string') {
    return string;
  }
  // TODO: Get rid of <a> tags in translations
  // Remove html tags, except <p> <b> <i> <li> <ul> <a> <strong>
  // Breakdown:
  //  (<\/?(?:p|b|i|li|ul|a|strong)\/?>) — 1st capturing group, selects allowed tags (opening and closing)
  //  (?:<\/?.*?\/?>) — non-capturing group (?:), matches all html tags
  //  $1 — keep matches from 1st capturing group as is, matches from non-capturing group will be omitted
  //  /gi — global (matches all occurrences) and case-insensitive
  // Test: https://regex101.com/r/cDa8jr/1
  return string.replace(/(<\/?(?:p|b|i|li|ul|a|strong)\/?>)|(?:<\/?.*?\/?>)/gi, '$1');
};

/**
 * Safely sets innerHTML to DOM element. Always use it instead of setting .innerHTML directly on element.
 * Sanitizes HTML by default. Use sanitize flag to control this behaviour.
 *
 * @param element
 * @param content
 * @param sanitize
 */
function setHtmlContent(element, content) {
  var sanitize = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;

  if (!element) {
    console.warn('Attempting to set HTML content on missing element');
  } else {
    element.innerHTML = sanitize ? sanitizeHtml(content) : content;
  }
}

/**
 * Helper function, check if the
 * @param alignment
 * @returns `true` if the supplied value is left or right, false otherwise
 */

var isValidAlignment = function isValidAlignment(alignment) {
  return _styleAlignmentPositions.styleAlignmentPositions.includes(alignment);
};

/**
 * Set widget alignment, allowed values are `left` and `right`
 *
 * @param elementId
 * @param alignment
 */
var setWidgetAlignment = function setWidgetAlignment(elementId, alignment) {
  if (!elementId) {
    console.warn('Trustpilot: cannot find stars wrapper element, please contact support!');
    return;
  }

  if (!alignment) {
    console.warn('Trustpilot: cannot apply widget alignment, please contact support!');
    return;
  }

  var isAlignmentValid = isValidAlignment(alignment);
  console.log('isAlignmentValid: ', isAlignmentValid);

  if (!isAlignmentValid) {
    console.warn('Trustpilot: ' + alignment + ' is not a valid widget alignment value, please contact support!');
    return;
  }

  var wapperElement = document.getElementById(elementId);

  if (!wapperElement) {
    console.error("Trustpilot: couldn't find the stars wrapper element, please contact support!");
    return;
  }

  // Note: Element's Id and Class Name are the same
  wapperElement.classList.add(elementId + '--' + alignment);
};

/**
 * Set popup alignment, allowed values are left and right
 * @param {string} alignment
 * @returns set the position of the popup of the Product Mini and Product Mini Imported widgets to `left` or `right`, as provided by the html data- field
 */

var setPopupAlignment = function setPopupAlignment(alignment) {
  if (!alignment) {
    console.warn('Trustpilot: cannot apply widget alignment, please contact support!');
    return;
  }

  var isAlignmentValid = isValidAlignment(alignment);

  if (!isAlignmentValid) {
    console.warn('Trustpilot: ' + alignment + ' is not a valid value for style alignment, please contact support!');
    return;
  }

  // Note: Element's Id and class name have the same value
  var widgetPopupWrapperElement = document.getElementById('tp-widget-wrapper');
  if (!widgetPopupWrapperElement) {
    console.error('Trustpilot: widget popup is not found, please contact support!');
    return;
  }

  var popupStyleAlignment = 'tp-widget-wrapper--' + alignment;
  widgetPopupWrapperElement.classList.add(popupStyleAlignment);
};

function makeTranslations(translations, string) {
  if (!string) {
    console.log('Missing translation string');
    return '';
  }
  return Object.keys(translations).reduce(function (result, key) {
    return result.split(key).join(translations[key]);
  }, string);
}

function removeElement(element) {
  if (!element || !element.parentNode) {
    console.log('Attempting to remove a non-existing element');
    return;
  }
  return element.parentNode.removeChild(element);
}

var showTrustBox = function showTrustBox(theme, hasReviews) {
  var body = document.getElementsByTagName('body')[0];
  var wrapper = document.getElementById('tp-widget-wrapper');

  (0, _dom.addClass)(body, theme);
  (0, _dom.addClass)(wrapper, 'visible');

  if (!hasReviews) {
    (0, _dom.addClass)(body, 'first-reviewer');
  }
};

// url can already have query params in it
var verifyQueryParamSeparator = function verifyQueryParamSeparator(url) {
  return '' + url + (url.indexOf('?') === -1 ? '?' : '&');
};

var addUtmParams = function addUtmParams(trustBoxName) {
  return function (url) {
    return verifyQueryParamSeparator(url) + 'utm_medium=trustbox&utm_source=' + trustBoxName;
  };
};

var regulateFollowForLocation = function regulateFollowForLocation(location) {
  return function (element) {
    if (location && element) {
      element.rel = 'nofollow';
    }
  };
};

var injectWidgetLinks = function injectWidgetLinks(baseData, utmTrustBoxId) {
  var linksClass = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'profile-url';
  var numberOfReviews = baseData.businessEntity.numberOfReviews.total,
      links = baseData.links;

  var items = [].slice.call(document.getElementsByClassName(linksClass));
  var baseUrl = numberOfReviews ? links.profileUrl : links.evaluateUrl;
  for (var i = 0; i < items.length; i++) {
    items[i].href = addUtmParams(utmTrustBoxId)(baseUrl);
  }
};

// Create a range of numbers, up to (but excluding) the argument.
// Written to support IE11.
var range = function range(num) {
  var result = [];
  while (num > 0) {
    result.push(result.length);
    num--;
  }
  return result;
};

// Shifts the given color to either lighter or darker based on the base value given.
// Positive values give you lighter color, negative darker.
var colorShift = function colorShift(col, amt) {
  var validateBounds = function validateBounds(v) {
    return v > 255 ? 255 : v < 0 ? 0 : v;
  };
  var usePound = false;

  if (col[0] === '#') {
    col = col.slice(1);
    usePound = true;
  }

  var num = parseInt(col, 16);
  if (!num) {
    return col;
  }

  var r = (num >> 16) + amt;
  r = validateBounds(r);

  var g = (num >> 8 & 0x00ff) + amt;
  g = validateBounds(g);

  var b = (num & 0x0000ff) + amt;
  b = validateBounds(b);

  var _map = [r, g, b].map(function (color) {
    return color <= 15 ? '0' + color.toString(16) : color.toString(16);
  });

  var _map2 = _slicedToArray(_map, 3);

  r = _map2[0];
  g = _map2[1];
  b = _map2[2];

  return (usePound ? '#' : '') + r + g + b;
};

var hexToRGBA = function hexToRGBA(hex) {
  var alpha = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1;

  var num = hex[0] === '#' ? parseInt(hex.slice(1), 16) : parseInt(hex, 16);
  var red = num >> 16;
  var green = num >> 8 & 0x00ff;
  var blue = num & 0x0000ff;
  return 'rgba(' + red + ',' + green + ',' + blue + ',' + alpha + ')';
};

var setTextColor = function setTextColor(textColor) {
  var textColorStyle = document.createElement('style');
  textColorStyle.appendChild(document.createTextNode('\n      * {\n        color: inherit !important;\n      }\n      body {\n        color: ' + textColor + ' !important;\n      }\n      .bold-underline {\n        border-bottom-color: ' + textColor + ' !important;\n      }\n      .bold-underline:hover {\n        border-color: ' + colorShift(textColor, -30) + ' !important;\n      }\n      .secondary-text {\n        color: ' + hexToRGBA(textColor, 0.6) + ' !important;\n      }\n      .secondary-text-arrow {\n        border-color: ' + hexToRGBA(textColor, 0.6) + ' transparent transparent transparent !important;\n      }\n      .read-more {\n        color: ' + textColor + ' !important;\n      }\n    '));
  document.head.appendChild(textColorStyle);
};

var setBorderColor = function setBorderColor(borderColor) {
  var borderColorStyle = document.createElement('style');
  borderColorStyle.appendChild(document.createTextNode('\n     * {\n        border-color: ' + borderColor + ' !important;\n      }\n    '));
  document.head.appendChild(borderColorStyle);
};

var setFont = function setFont(fontFamily) {
  var widgetRootUri = (0, _rootUri2.default)();
  var fontFamilyNormalizedForUrl = fontFamily.replace(/\s/g, '-').toLowerCase();
  var fontLink = document.createElement('link');
  fontLink.rel = 'stylesheet';
  // we are using the following three weights in most of our TrustBoxes
  // in future iterations, we can optimize the bytes transferred by having a list of weights per TrustBox
  fontLink.href = widgetRootUri + '/fonts/' + fontFamilyNormalizedForUrl + '.css';
  document.head.appendChild(fontLink);

  var cleanFontName = fontFamily.replace(/\+/g, ' ');
  var fontStyle = document.createElement('style');
  fontStyle.appendChild(document.createTextNode('\n    * {\n      font-family: inherit !important;\n    }\n    body {\n      font-family: "' + cleanFontName + '", sans-serif !important;\n    }\n    '));
  document.head.appendChild(fontStyle);
};

var setHtmlLanguage = function setHtmlLanguage(language) {
  document.documentElement.setAttribute('lang', language);
};

var sanitizeColor = function sanitizeColor(color) {
  var hexRegExp = /^#(?:[\da-fA-F]{3}){1,2}$/;
  return typeof color === 'string' && hexRegExp.test(color) ? color : null;
};

var handlePopoverPosition = function handlePopoverPosition(label, popover, container, popUpArrow) {
  var popoverRect = popover.getBoundingClientRect();
  var containerRect = container.getBoundingClientRect();
  var labelRect = label.getBoundingClientRect();

  if (popoverRect.left < containerRect.left) {
    // We need to stick the popover to the left side of the container
    // Because the `left` and `right` values are relative to the parent,
    // we need to make the following calculation to find where to stick the popover
    popover.style.left = containerRect.left - labelRect.left + 'px';
    popover.style.right = 'auto';
    // Moving the arrow by the distance of the popover shift over X axis
    var newPopupRect = popover.getBoundingClientRect();
    var currentLeftValue = getComputedStyle(popUpArrow).left;
    popUpArrow.style.left = 'calc(' + currentLeftValue + ' + ' + Math.floor(popoverRect.left - newPopupRect.left) + 'px)';
  } else if (popoverRect.right > containerRect.right) {
    // We need to stick the popover to the right side of the container
    // Because the `left` and `right` values are relative to the parent,
    // we need to make the following calculation to find where to stick the popover
    popover.style.right = labelRect.right - containerRect.right + 'px';
    popover.style.left = 'auto';
    // Moving the arrow by the distance of the popover shift over X axis
    var _newPopupRect = popover.getBoundingClientRect();
    var _currentLeftValue = getComputedStyle(popUpArrow).left;
    popUpArrow.style.left = 'calc(' + _currentLeftValue + ' + ' + Math.floor(popoverRect.right - _newPopupRect.right) + 'px)';
  }
};

var sortAttributeRatings = function sortAttributeRatings(attributeRatingsArray) {
  var sortByName = function sortByName(a, b) {
    return a.name.localeCompare(b.name);
  };

  var starAttributes = attributeRatingsArray.filter(function (x) {
    return x.type === 'range_1to5';
  }).sort(sortByName);
  var scaleAttributes = attributeRatingsArray.filter(function (x) {
    return x.type === 'scale';
  }).sort(sortByName);

  return [].concat(_toConsumableArray(starAttributes), _toConsumableArray(scaleAttributes));
};

var getTrustpilotBusinessUnitId = function getTrustpilotBusinessUnitId() {
  // This will be substituted within the buildscripts
  var buid = '#{TrustpilotBusinessUnitId}';
  return buid.indexOf('#') === 0 ? '46d6a890000064000500e0c3' : buid;
};

exports.addEventListener = addEventListener;
exports.addUtmParams = addUtmParams;
exports.getOnPageReady = getOnPageReady;
/* common-shake removed: exports.getTrustpilotBusinessUnitId = */ void getTrustpilotBusinessUnitId;
/* common-shake removed: exports.injectWidgetLinks = */ void injectWidgetLinks;
exports.insertNumberSeparator = insertNumberSeparator;
/* common-shake removed: exports.handlePopoverPosition = */ void handlePopoverPosition;
exports.makeTranslations = makeTranslations;
/* common-shake removed: exports.range = */ void range;
/* common-shake removed: exports.regulateFollowForLocation = */ void regulateFollowForLocation;
exports.removeElement = removeElement;
exports.sanitizeColor = sanitizeColor;
/* common-shake removed: exports.sanitizeHtml = */ void sanitizeHtml;
exports.sanitizeHtmlProp = sanitizeHtmlProp;
/* common-shake removed: exports.setBorderColor = */ void setBorderColor;
exports.setHtmlContent = setHtmlContent;
exports.setHtmlLanguage = setHtmlLanguage;
exports.setFont = setFont;
/* common-shake removed: exports.setPopupAlignment = */ void setPopupAlignment;
exports.setTextColor = setTextColor;
exports.setTextContent = setTextContent;
/* common-shake removed: exports.setWidgetAlignment = */ void setWidgetAlignment;
exports.showTrustBox = showTrustBox;
/* common-shake removed: exports.sortAttributeRatings = */ void sortAttributeRatings;

},{"./dom":7,"./models/styleAlignmentPositions":10,"./rootUri":12}],19:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _communication = require('../communication');

var _errorFallback = require('./templates/errorFallback');

var FALLBACK_DELAY = 500;

/**
 * Makes sure that the widget is initialized only when the bootstrapper is present.
 *
 * Sends a "ping" message to the bootstrapper and waits for a "pong" reply before initializing the widget.
 *
 * @param {Function} onInit the callback to be executed when the init is done.
 */
var init = function init(onInit) {
  var initialized = false;
  (0, _communication.onPong)(function () {
    initialized = true;
    if (typeof onInit === 'function') {
      onInit();
    } else {
      console.warn('`onInit` not supplied');
    }
  });

  (0, _communication.ping)();

  // we want to avoid rendering the fallback right away in case the "pong" message from the bootstrapper comes back immediately
  // this way we will avoid a flicker from "empty screen" -> "fallback" -> "TrustBox" and have "empty screen" -> "TrustBox"
  setTimeout(function () {
    if (!initialized) {
      (0, _errorFallback.errorFallback)();
    }
  }, FALLBACK_DELAY);
};

exports.default = init;

},{"../communication":6,"./templates/errorFallback":20}],23:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.populateStars = /* common-shake removed: exports.makeStars = */ undefined;

var _templating = require('../templating');

var _dom = require('../../dom');

var _utils = require('../../utils');

var _translations = require('../translations');

var _stars = require('../assets/stars');

var makeStars = function makeStars(_ref) {
  var num = _ref.num,
      _ref$trustScore = _ref.trustScore,
      trustScore = _ref$trustScore === undefined ? null : _ref$trustScore,
      _ref$wrapperClass = _ref.wrapperClass,
      wrapperClass = _ref$wrapperClass === undefined ? '' : _ref$wrapperClass,
      color = _ref.color,
      locale = _ref.locale,
      translations = _ref.translations;

  var fullPart = Math.floor(num);
  var halfPart = num === fullPart ? '' : ' tp-stars--' + fullPart + '--half';
  var sanitizedColor = (0, _utils.sanitizeColor)(color);
  return (0, _templating.div)({ class: wrapperClass },
  // add a different class so that styles from widgets-styleguide do not apply
  (0, _templating.mkElemWithSvg)(_stars.stars, '' + (sanitizedColor ? 'tp-stars-custom-color' : 'tp-stars tp-stars--' + fullPart + halfPart), { rating: num, trustScore: trustScore || num, color: sanitizedColor, locale: locale, translations: translations }));
};

var populateStars = function populateStars(_ref2) {
  var _ref2$businessEntity = _ref2.businessEntity,
      stars = _ref2$businessEntity.stars,
      trustScore = _ref2$businessEntity.trustScore,
      total = _ref2$businessEntity.numberOfReviews.total,
      translations = _ref2.translations;
  var starsContainer = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'tp-widget-stars';
  var starsColor = arguments[2];
  var locale = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : _translations.defaultLocale;

  var sanitizedColor = (0, _utils.sanitizeColor)(starsColor);
  var container = typeof starsContainer === 'string' ? document.getElementById(starsContainer) : starsContainer;
  // Ensure we properly handle empty review state - we sometimes get a rating
  // back from the API even where we have no reviews, so explicitly check.
  var displayedStars = total ? stars : 0;

  (0, _dom.populateElements)([{
    element: container,
    string: makeStars({
      num: displayedStars,
      trustScore: trustScore,
      color: sanitizedColor,
      locale: locale,
      translations: translations
    })
  }]);
};

/* common-shake removed: exports.makeStars = */ void makeStars;
exports.populateStars = populateStars;

},{"../../dom":7,"../../utils":26,"../assets/stars":18,"../templating":24,"../translations":25}],22:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.populateLogo = /* common-shake removed: exports.makeLogo = */ undefined;

var _templating = require('../templating');

var _dom = require('../../dom');

var _logo = require('../assets/logo');

var makeLogo = function makeLogo() {
  return (0, _templating.mkElemWithSvg)(_logo.logo);
};

var populateLogo = function populateLogo() {
  var logoContainer = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'tp-widget-logo';

  var container = typeof logoContainer === 'string' ? document.getElementById(logoContainer) : logoContainer;

  (0, _dom.populateElements)([{
    element: container,
    string: makeLogo()
  }]);
};

/* common-shake removed: exports.makeLogo = */ void makeLogo;
exports.populateLogo = populateLogo;

},{"../../dom":7,"../assets/logo":17,"../templating":24}],14:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
/* common-shake removed: exports.fetchServiceRevieMultipleData = */ exports.fetchServiceReviewData = /* common-shake removed: exports.constructTrustBoxAndComplete = */ /* common-shake removed: exports.fetchProductReview = */ /* common-shake removed: exports.fetchProductData = */ undefined;

var _fetchData = require('./fetchData');

var _productReviews = require('./productReviews');

var fetchServiceReviewData = function fetchServiceReviewData(templateId) {
  return function (fetchParams, constructTrustBox, passToPopup) {
    (0, _fetchData.fetchData)('/trustbox-data/' + templateId)(fetchParams, constructTrustBox, passToPopup, _fetchData.hasServiceReviews);
  };
};

var fetchServiceRevieMultipleData = function fetchServiceRevieMultipleData(templateId) {
  return function (fetchParams, constructTrustBox, passToPopup) {
    (0, _fetchData.multiFetchData)('/trustbox-data/' + templateId)(fetchParams, constructTrustBox, passToPopup, _fetchData.hasServiceReviewsMultiFetch);
  };
};

/* common-shake removed: exports.fetchProductData = */ void _productReviews.fetchProductData;
/* common-shake removed: exports.fetchProductReview = */ void _productReviews.fetchProductReview;
/* common-shake removed: exports.constructTrustBoxAndComplete = */ void _fetchData.constructTrustBoxAndComplete;
exports.fetchServiceReviewData = fetchServiceReviewData;
/* common-shake removed: exports.fetchServiceRevieMultipleData = */ void fetchServiceRevieMultipleData;

},{"./fetchData":13,"./productReviews":15}],2:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.apiCall = undefined;

var _xhr = require('../xhr');

var _xhr2 = _interopRequireDefault(_xhr);

var _queryString = require('../queryString');

var _rootUri = require('../rootUri');

var _rootUri2 = _interopRequireDefault(_rootUri);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// Make a random ID where an apiCall requires one.
var makeId = function makeId(numOfChars) {
  var text = '';
  var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  for (var i = 0; i < numOfChars; i++) {
    text += possible.charAt(Math.floor(Math.random() * possible.length));
  }
  return text;
};

/* eslint-disable compat/compat */
var apiCall = function apiCall(uri, params) {
  return new Promise(function (resolve, fail) {
    var values = void 0;
    var url = void 0;

    if (uri.indexOf('/') === 0) {
      values = params || {};

      var _getQuerystringAsObje = (0, _queryString.getAsObject)(),
          token = _getQuerystringAsObje.token;

      if (token) {
        values.random = makeId(20);
      }
    }

    if (uri.indexOf('http') === 0) {
      // is a full url from a paging link, ensure https
      url = uri.replace(/^https?:/, 'https:');
    } else if (uri.indexOf('/') === 0) {
      // is a regular "/v1/..." add domain for local testing (value is empty in prod)
      url = (0, _rootUri2.default)() + uri;
    } else {
      // weird/broken url
      return fail();
    }

    return (0, _xhr2.default)({
      url: url,
      data: values,
      success: resolve,
      error: fail
    });
  });
};

exports.apiCall = apiCall;
/* eslint-enable compat/compat */

},{"../queryString":11,"../rootUri":12,"../xhr":27}],27:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
/* global ActiveXObject */

function isIE() {
  var myNav = navigator.userAgent.toLowerCase();
  return myNav.indexOf('msie') !== -1 ? parseInt(myNav.split('msie')[1]) : false;
}

// adapted (stolen) from https://github.com/toddmotto/atomic

function parse(req) {
  try {
    return JSON.parse(req.responseText);
  } catch (e) {
    return req.responseText;
  }
}

// http://stackoverflow.com/a/1714899
function toQueryString(obj) {
  var str = [];
  for (var p in obj) {
    if (obj.hasOwnProperty(p)) {
      str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]));
    }
  }
  return str.join('&');
}

function noop() {}

function makeRequest(params) {
  var XMLHttpRequest = window.XMLHttpRequest || ActiveXObject;
  var request = new XMLHttpRequest('MSXML2.XMLHTTP.3.0');
  request.open(params.type, params.url, true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function () {
    if (request.readyState === 4) {
      if (request.status >= 200 && request.status < 300) {
        params.success(parse(request));
      } else {
        params.error(parse(request));
      }
    }
  };

  request.send(params.data);
}

/* IE9-compatible request function.

IE9 does not permit cross-origin HTTP requests in the usual way. It also does
not permit a request to be made to a URI with a different protocol from that
of the page, e.g. an HTTPS request from an HTTP page.

This function makes requests in a manner compatible with IE9's limitations.
*/
function makeRequestIE(params) {
  var request = new window.XDomainRequest();
  var protocol = window.location.protocol;
  params.url = params.url.replace(/https?:/, protocol);
  request.open(params.type, params.url);
  request.onload = function () {
    params.success(parse(request));
  };
  request.onerror = function () {
    params.error(parse(request));
  };

  setTimeout(function () {
    request.send(params.data);
  }, 0);
}

function xhr(options) {
  var params = {
    type: options.type || 'GET',
    error: options.error || noop,
    success: options.success || noop,
    data: options.data,
    url: options.url || ''
  };

  if (params.type === 'GET' && params.data) {
    params.url = params.url + '?' + toQueryString(params.data);
    delete params.data;
  }

  if (isIE() && isIE() <= 9) {
    makeRequestIE(params);
  } else {
    makeRequest(params);
  }
}

exports.default = xhr;

},{}],12:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

exports.default = function () {
  var host = '#{WidgetApi.Host}';
  return host.indexOf('#') === 0 ? 'https://widget.tp-staging.com' : host;
};

},{}],3:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _fn = require('../../fn');

var _call = require('../call');

var _util = require('./util');

var _responseProcessor = require('./responseProcessor');

var _responseProcessor2 = _interopRequireDefault(_responseProcessor);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _objectWithoutProperties(obj, keys) { var target = {}; for (var i in obj) { if (keys.indexOf(i) >= 0) continue; if (!Object.prototype.hasOwnProperty.call(obj, i)) continue; target[i] = obj[i]; } return target; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var NO_REVIEWS_ERROR = 'No reviews available';

/**
 * This class provides reviews on request of a consumer. It collects reviews
 * through paginated API calls, and then provides one page of reviews on request
 * from the consumer.
 *
 * Three methods are exposed as intended for use: {@link ReviewFetcher#consumeReviews},
 * {@link ReviewFetcher#produceReviews}, and {@link ReviewFetcher#hasMoreReviews}. Other
 * methods should be considered private.
 */

var ReviewFetcher = function () {
  /**
   * Construct a ReviewFetcher.
   *
   * The constructor takes an object containing options and data required to
   * obtain and produce reviews for consumption.
   *
   * @param {Object} args - An object containing the arguments below.
   * @param {number} args.reviewsPerPage - The number of reviews to provide per
   * request.
   * @param {boolean} args.includeImportedReviews - Whether to include imported
   * reviews in the reviews provided.
   * @param {Object} args.baseData - The baseData response received from a base-data
   * call.
   * @param {Object} args.wrapArgs - An arbitrary set of arguments to add to
   * the data provided to the callback in {@link ReviewFetcher#consumeReviews}.
   */
  function ReviewFetcher(_ref) {
    var reviewsPerPage = _ref.reviewsPerPage,
        includeImportedReviews = _ref.includeImportedReviews,
        baseData = _ref.baseData,
        wrapArgs = _objectWithoutProperties(_ref, ['reviewsPerPage', 'includeImportedReviews', 'baseData']);

    _classCallCheck(this, ReviewFetcher);

    // Get next page links from a base data response.
    var getBaseDataNextPageLinks = (0, _util.getNextPageLinks)(function (responseKey) {
      return (0, _fn.pipeMaybe)((0, _fn.prop)(responseKey), (0, _fn.prop)('links'), (0, _fn.prop)('nextPage'));
    });

    this.reviewsPerPage = reviewsPerPage;
    this.includeImportedReviews = includeImportedReviews;
    this.baseData = baseData;
    this.nextPage = getBaseDataNextPageLinks(baseData, includeImportedReviews);
    this.wrapArgs = wrapArgs;

    this.reviews = this._makeResponseProcessor(baseData).getReviews();
  }

  /**
   * Consume a number of reviews using a callback function.
   *
   * This method gets one page of reviews, and combines this with the data in
   * the wrapArgs field and passes it all to a callback. The return value is
   * wrapped in an anonymous function to make it suitable for use within event
   * handlers.
   *
   * @param {Function} callback - A function to call with a set of review data.
   */


  _createClass(ReviewFetcher, [{
    key: 'consumeReviews',
    value: function consumeReviews(callback) {
      var _this = this;

      return function () {
        return _this.produceReviews().then(function (reviews) {
          return callback(_extends({}, _this.wrapArgs, {
            baseData: _this.baseData,
            reviews: reviews,
            hasMoreReviews: _this.hasMoreReviews,
            loadMoreReviews: _this.consumeReviews.bind(_this)
          }));
        }).catch(function (err) {
          if (err === NO_REVIEWS_ERROR) {
            return callback(_extends({}, _this.wrapArgs, {
              baseData: _this.baseData,
              reviews: [],
              hasMoreReviews: false,
              loadMoreReviews: _this.consumeReviews.bind(_this)
            }));
          } else {
            // Rethrow error which is unexpected
            throw err;
          }
        });
      };
    }

    /**
     * Produce a number of reviews.
     *
     * This method produces one page of reviews. It may require to fetch additional
     * reviews from an API if there are insufficent reviews available locally. The
     * reviews are thus returned wrapped in a Promise.
     */

  }, {
    key: 'produceReviews',
    value: function produceReviews() {
      var _this2 = this;

      var processResponse = function processResponse(response) {
        var _reviews;

        var responseProcessor = _this2._makeResponseProcessor(response);
        _this2.nextPage = responseProcessor.getNextPageLinks();
        (_reviews = _this2.reviews).push.apply(_reviews, _toConsumableArray(responseProcessor.getReviews()));
        return _this2._takeReviews();
      };

      if (this.reviews.length === 0) {
        // eslint-disable-next-line compat/compat
        return Promise.reject(NO_REVIEWS_ERROR);
      }
      return this.reviewsPerPage >= this.reviews.length ? this._fetchReviews().then(processResponse) : // eslint-disable-next-line compat/compat
      Promise.resolve(this._takeReviews());
    }

    /**
     * Flag whether more reviews are available for consumption.
     *
     * Where true, this means it is possible to load more reviews. If false, no
     * more reviews are available.
     */

  }, {
    key: '_takeReviews',


    // Private Methods //

    /**
     * Take a page of reviews from internal cache of reviews, removing these and
     * returning them from the method.
     */
    value: function _takeReviews() {
      return this.reviews.splice(0, this.reviewsPerPage);
    }

    /**
     * Fetch more reviews from the API.
     */

  }, {
    key: '_fetchReviews',
    value: function _fetchReviews() {
      return (0, _fn.promiseAllObject)((0, _fn.mapObject)(_call.apiCall, this.nextPage));
    }

    /**
     * Construct a {@link ResponseProcessor} instance using properties from this instance.
     */

  }, {
    key: '_makeResponseProcessor',
    value: function _makeResponseProcessor(response) {
      return new _responseProcessor2.default(response, {
        includeImportedReviews: this.includeImportedReviews,
        displayName: this.baseData.businessEntity.displayName
      });
    }
  }, {
    key: 'hasMoreReviews',
    get: function get() {
      return this.reviews.length > 0;
    }
  }]);

  return ReviewFetcher;
}();

exports.default = ReviewFetcher;

},{"../../fn":8,"../call":2,"./responseProcessor":4,"./util":5}],8:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

// Convert reduce method into a function
var reduce = function reduce(f) {
  return function (init) {
    return function (xs) {
      return xs.reduce(f, init);
    };
  };
};

// Convert filter method into a function
var filter = function filter(p) {
  return function (xs) {
    return xs.filter(p);
  };
};

// Convert map method into a function
var map = function map(f) {
  return function (xs) {
    return xs.map(f);
  };
};

// Implementation of map, but for an object. Values are replaced with the result
// of calling the passed function of them; keys remain unchanged.
var mapObject = function mapObject(f, obj) {
  return Object.keys(obj).reduce(function (all, k) {
    return _extends({}, all, _defineProperty({}, k, f(obj[k])));
  }, {});
};

// Transforms an object containing arbitrary keys, and promise values, into a
// promise-wrapped object, with the same keys and the result of resolving each
// promise as values.
var promiseAllObject = function promiseAllObject(obj) {
  var keys = Object.keys(obj);
  var values = keys.map(function (k) {
    return obj[k];
  });
  // eslint-disable-next-line compat/compat
  return Promise.all(values).then(function (promises) {
    return promises.reduce(function (all, promise, idx) {
      return _extends({}, all, _defineProperty({}, keys[idx], promise));
    }, {});
  });
};

/**
 * Convert an array containing pairs of values into an object.
 *
 *   [[k1, v1], [k2, v2], ... ] -> { [k1]: v1, [k2]: v2, ... }
 */
var pairsToObject = function pairsToObject(pairs) {
  return pairs.reduce(function (obj, _ref) {
    var _ref2 = _slicedToArray(_ref, 2),
        k = _ref2[0],
        v = _ref2[1];

    return _extends({}, obj, _defineProperty({}, k, v));
  }, {});
};

var isNullary = function isNullary(value) {
  return typeof value === 'undefined' || value === null;
};

var isNullaryOrFalse = function isNullaryOrFalse(value) {
  return isNullary(value) || value === false;
};

// Filter out all null or undefined values from an object.
var rejectNullaryValues = function rejectNullaryValues(obj) {
  return Object.keys(obj).reduce(function (newObj, key) {
    return _extends({}, newObj, isNullary(obj[key]) ? {} : _defineProperty({}, key, obj[key]));
  }, {});
};

/**
 * Split an array of values into chunks of a given size.
 *
 * If the number of values does not divide evenly into the chunk size, the
 * final chunk will be smaller than chunkSize.
 *
 *   chunk 2 [a, b, c, d, e, f, g] -> [[a, b], [c, d], [e, f], [g]]
 */
var chunk = function chunk(chunkSize) {
  return reduce(function (chunks, val, idx) {
    var lastChunk = chunks[chunks.length - 1];
    var isNewChunk = idx % chunkSize === 0;
    var newChunk = isNewChunk ? [val] : [].concat(_toConsumableArray(lastChunk), [val]);
    return [].concat(_toConsumableArray(chunks.slice(0, chunks.length - (isNewChunk ? 0 : 1))), [newChunk]);
  })([]);
};

/**
 * Split an array of values into chunks of a given size, and then transpose values.
 *
 * This is equivalent to {@link 'chunk'}, but with the values transposed:
 *
 *   chunkTranspose 2 [a, b, c, d, e, f, g] -> [[a, c, e, g], [b, d, f]]
 *
 * The transposition has the effect of turning an array of x chunks of size n,
 * into an array of n chunks of size x.
 */
var chunkTranspose = function chunkTranspose(chunkSize) {
  return reduce(function (chunks, val, idx) {
    var chunkIdx = idx % chunkSize;
    var newChunk = [].concat(_toConsumableArray(chunks[chunkIdx] || []), [val]);
    return [].concat(_toConsumableArray(chunks.slice(0, chunkIdx)), [newChunk], _toConsumableArray(chunks.slice(chunkIdx + 1)));
  })([]);
};

/**
 * Compose a series of functions together.
 *
 * Equivalent to applying an array of functions to a value, right to left.
 *
 *   compose(f, g, h)(x) === f(g(h(x)))
 *
 */
var compose = function compose() {
  for (var _len = arguments.length, fs = Array(_len), _key = 0; _key < _len; _key++) {
    fs[_key] = arguments[_key];
  }

  return function (x) {
    return fs.reduceRight(function (val, f) {
      return f(val);
    }, x);
  };
};

// Pipe a value through a series of functions which terminates immediately on
// receiving a nullary value.
var pipeMaybe = function pipeMaybe() {
  for (var _len2 = arguments.length, fs = Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
    fs[_key2] = arguments[_key2];
  }

  return function (x) {
    return fs.reduce(function (val, f) {
      return isNullary(val) ? val : f(val);
    }, x);
  };
};

// Get first value from an array
var first = function first(_ref4) {
  var _ref5 = _slicedToArray(_ref4, 1),
      x = _ref5[0];

  return x;
};

// First first value matching predicate p in an array of values.
var find = function find(p) {
  return pipeMaybe(filter(p), first);
};

// Get a value from an object at a given key.
var prop = function prop(k) {
  return function () {
    var obj = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    return obj[k];
  };
};

// Get a value from an object at a given key if it exists.
var propMaybe = function propMaybe(k) {
  return function () {
    var obj = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    return obj[k] || obj;
  };
};

// Test if a value is false or nullary, returning null if true, or a second value if false.
// Intended for use within a pipeMaybe where you want to terminate execution where
// an arbitrary value is null.
var guard = function guard(p) {
  return function (x) {
    return isNullaryOrFalse(p) ? null : x;
  };
};

/* common-shake removed: exports.chunk = */ void chunk;
/* common-shake removed: exports.chunkTranspose = */ void chunkTranspose;
exports.compose = compose;
/* common-shake removed: exports.filter = */ void filter;
exports.find = find;
/* common-shake removed: exports.first = */ void first;
exports.guard = guard;
exports.map = map;
exports.mapObject = mapObject;
exports.pairsToObject = pairsToObject;
exports.pipeMaybe = pipeMaybe;
exports.promiseAllObject = promiseAllObject;
exports.prop = prop;
exports.propMaybe = propMaybe;
exports.rejectNullaryValues = rejectNullaryValues;

},{}],5:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.getNextPageLinks = undefined;

var _fn = require('../../fn');

/**
 * Get next page links from a response.
 *
 * This function take a getter function, used to extract a particular type of link,
 * either for productReviews or importedProductReviews. It returns a function which
 * take a response and a flag to indicate whether to include imported reviews. This
 * can then be called to obtain available next page links.
 */
var getNextPageLinks = function getNextPageLinks(getter) {
  return function (response) {
    var includeImportedReviews = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

    var productReviews = getter('productReviews')(response);
    var importedProductReviews = (0, _fn.pipeMaybe)((0, _fn.guard)(includeImportedReviews), getter('importedProductReviews'))(response);
    return (0, _fn.rejectNullaryValues)({
      productReviews: productReviews,
      importedProductReviews: importedProductReviews
    });
  };
};

exports.getNextPageLinks = getNextPageLinks;

},{"../../fn":8}],4:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _fn = require('../../fn');

var _util = require('./util');

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * This class processes an API response containing reviews and pagination
 * data.
 */
var ReviewResponseProcessor = function () {
  /**
   * Create a ReviewResponseProcessor instance.
   *
   * Takes an API response object for processing, together with a short list
   * of options for processing and annotating reviews.
   */
  function ReviewResponseProcessor(response, _ref) {
    var includeImportedReviews = _ref.includeImportedReviews,
        displayName = _ref.displayName;

    _classCallCheck(this, ReviewResponseProcessor);

    this.response = response;
    this.includeImportedReviews = includeImportedReviews;
    this.displayName = displayName;
  }

  /**
   * Get a combined list of reviews from the API response.
   *
   * This method extracts all reviews from the response, including optionally
   * imported reviews, and then combines and sorts these by date, descending.
   */


  _createClass(ReviewResponseProcessor, [{
    key: 'getReviews',
    value: function getReviews() {
      var _this = this;

      var _response = this.response,
          productReviews = _response.productReviews,
          importedProductReviews = _response.importedProductReviews;

      var orderByCreatedAtDesc = function orderByCreatedAtDesc(_ref2, _ref3) {
        var c1 = _ref2.createdAt;
        var c2 = _ref3.createdAt;
        return new Date(c2) - new Date(c1);
      };
      var productReviewsList = (0, _fn.pipeMaybe)((0, _fn.propMaybe)('productReviews'), (0, _fn.propMaybe)('reviews'))(productReviews) || [];

      var importedReviewsList = (0, _fn.pipeMaybe)((0, _fn.guard)(this.includeImportedReviews), (0, _fn.propMaybe)('importedProductReviews'), (0, _fn.propMaybe)('productReviews'), (0, _fn.map)(function (review) {
        return _extends({}, review, {
          verifiedBy: review.type === 'External' ? review.source ? review.source.name : _this.displayName : _this.displayName
        });
      }))(importedProductReviews) || [];

      return [].concat(_toConsumableArray(productReviewsList), _toConsumableArray(importedReviewsList)).sort(orderByCreatedAtDesc);
    }

    /**
     * Get an object containing links to the next page URIs contained within the
     * API response.
     */

  }, {
    key: 'getNextPageLinks',
    value: function getNextPageLinks() {
      // Get next page links from a pagination response.
      var getOldPaginationNextPageLinks = (0, _util.getNextPageLinks)(function (responseKey) {
        return (0, _fn.pipeMaybe)((0, _fn.prop)(responseKey), (0, _fn.prop)('links'), (0, _fn.find)(function (link) {
          return link.rel === 'next-page';
        }), (0, _fn.prop)('href'));
      });

      var getNewPaginationNextPageLinks = (0, _util.getNextPageLinks)(function (responseKey) {
        return (0, _fn.pipeMaybe)((0, _fn.prop)(responseKey), (0, _fn.prop)(responseKey), (0, _fn.prop)('links'), (0, _fn.prop)('nextPage'));
      });

      var newLinks = getNewPaginationNextPageLinks(this.response, this.includeImportedReviews);
      var oldLinks = getOldPaginationNextPageLinks(this.response, this.includeImportedReviews);

      return _extends({}, oldLinks, newLinks);
    }
  }]);

  return ReviewResponseProcessor;
}();

exports.default = ReviewResponseProcessor;

},{"../../fn":8,"./util":5}],6:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
/* common-shake removed: exports.scrollToTrustBox = */ exports.onPong = exports.ping = /* common-shake removed: exports.isPopupToggleMessage = */ /* common-shake removed: exports.isAPIDataMessage = */ exports.sendAPIDataMessage = exports.isLoadedMessage = exports.setListener = /* common-shake removed: exports.resizeHeight = */ /* common-shake removed: exports.setStyles = */ /* common-shake removed: exports.loaded = */ /* common-shake removed: exports.focusModal = */ /* common-shake removed: exports.hideModal = */ /* common-shake removed: exports.showModal = */ /* common-shake removed: exports.focusPopup = */ /* common-shake removed: exports.hidePopup = */ /* common-shake removed: exports.showPopup = */ /* common-shake removed: exports.hideTrustBox = */ /* common-shake removed: exports.createModal = */ /* common-shake removed: exports.createPopup = */ /* common-shake removed: exports.send = */ undefined;

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _utils = require('./utils.js');

var wparent = window.parent;
var messageQueue = [];
var defaultOptions = {
  command: 'createIFrame',
  position: 'center top',
  show: false,
  source: 'popup.html',
  queryString: ''
};
var popupOptions = {
  name: 'popup',
  modal: false,
  styles: {
    height: '300px',
    width: ''
  }
};
var modalOptions = {
  name: 'modal',
  modal: true,
  styles: {
    width: '100%',
    height: '100%',
    position: 'fixed',
    left: '0',
    right: '0',
    top: '0',
    bottom: '0',
    margin: '0 auto',
    zindex: 99
  }
};

var id = null;
var listenerCallbacks = [];

function sendMessage(message) {
  if (id) {
    message.widgetId = id;
    message = JSON.stringify(message); // This is to make it IE8 compatible
    wparent.postMessage(message, '*');
  } else {
    messageQueue.push(message);
  }
}

function sendMessageTo(target) {
  return function (message) {
    var payload = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    return sendMessage(_extends({}, payload, {
      message: message,
      command: 'message',
      name: target
    }));
  };
}

function sendQueue() {
  while (messageQueue.length) {
    sendMessage(messageQueue.pop());
  }
}

function createPopupIframe(options) {
  sendMessage(_extends({}, defaultOptions, popupOptions, options));
}

function createModalIframe(options) {
  sendMessage(_extends({}, defaultOptions, modalOptions, options));
}

function setStyles(styles, optionalIframeName) {
  sendMessage({ command: 'setStyle', name: optionalIframeName, style: styles });
}

function showIframe(iframeName) {
  sendMessage({ command: 'show', name: iframeName });
  sendMessageTo('main')(iframeName + ' toggled', { visible: true });
}

function hideIframe(iframeName) {
  sendMessage({ command: 'hide', name: iframeName });
  sendMessageTo('main')(iframeName + ' toggled', { visible: false });
}

function focusIframe(iframeName) {
  sendMessage({ command: 'focus', name: iframeName });
}

function sendLoadedMessage() {
  sendMessage({ command: 'loaded' });
}

function isLoadedMessage(message) {
  return message === 'loaded';
}

/**
 * Send data obtained from an API call to a popup iframe.
 */
function sendAPIDataMessage(data) {
  sendMessageTo('popup')('API data', data);
}

/**
 * Test if two messages are of the same type.
 *
 * Ignores any additional data contained within the message.
 */
function areMatchingMessages(message, otherMessage) {
  return ['message', 'command', 'name'].every(function (key) {
    return message[key] && otherMessage[key] && message[key] === otherMessage[key];
  });
}

function isAPIDataMessage(message) {
  return areMatchingMessages(message, {
    command: 'message',
    name: 'popup',
    message: 'API data'
  });
}

function isPopupToggleMessage(message) {
  return areMatchingMessages(message, {
    command: 'message',
    name: 'main',
    message: 'popup toggled'
  });
}

function addCallbackFunction(func) {
  listenerCallbacks.push(func);
}

function hideMainIframe() {
  hideIframe('main');
}

function showPopupIframe() {
  showIframe('popup');
}

function hidePopupIframe() {
  hideIframe('popup');
}

function focusPopupIframe() {
  focusIframe('popup');
}

function showModalIframe() {
  showIframe('modal');
}

function hideModalIframe() {
  hideIframe('modal');
}

function focusModalIframe() {
  focusIframe('modal');
}

var sendPing = function sendPing() {
  return sendMessage({ command: 'ping' });
};

var onPong = function onPong(cb) {
  var pong = function pong(event) {
    if (event.data.command === 'pong') {
      // eslint-disable-next-line callback-return
      cb(event);
    }
  };
  addCallbackFunction(pong);
};

function resizeHeight(optionalHeight, optionalIframeName) {
  var body = document.getElementsByTagName('body')[0];
  sendMessage({
    command: 'resize-height',
    name: optionalIframeName,
    height: optionalHeight || body.offsetHeight
  });
}

function scrollToTrustBox(targets) {
  sendMessage({
    command: 'scrollTo',
    targets: targets
  });
}

(0, _utils.addEventListener)(window, 'message', function (event) {
  if (typeof event.data !== 'string') {
    return;
  }

  var e = void 0;
  try {
    e = { data: JSON.parse(event.data) }; // This is to make it IE8 compatible
  } catch (e) {
    return; // probably not for us
  }

  if (e.data.command === 'setId') {
    id = e.data.widgetId;
    sendQueue();
  } else {
    for (var i = 0; i < listenerCallbacks.length; i++) {
      var callback = listenerCallbacks[i];
      // eslint-disable-next-line callback-return
      callback(e);
    }
  }
});

/* common-shake removed: exports.send = */ void sendMessage;
/* common-shake removed: exports.createPopup = */ void createPopupIframe;
/* common-shake removed: exports.createModal = */ void createModalIframe;
/* common-shake removed: exports.hideTrustBox = */ void hideMainIframe;
/* common-shake removed: exports.showPopup = */ void showPopupIframe;
/* common-shake removed: exports.hidePopup = */ void hidePopupIframe;
/* common-shake removed: exports.focusPopup = */ void focusPopupIframe;
/* common-shake removed: exports.showModal = */ void showModalIframe;
/* common-shake removed: exports.hideModal = */ void hideModalIframe;
/* common-shake removed: exports.focusModal = */ void focusModalIframe;
/* common-shake removed: exports.loaded = */ void sendLoadedMessage;
/* common-shake removed: exports.setStyles = */ void setStyles;
/* common-shake removed: exports.resizeHeight = */ void resizeHeight;
exports.setListener = addCallbackFunction;
exports.isLoadedMessage = isLoadedMessage;
exports.sendAPIDataMessage = sendAPIDataMessage;
/* common-shake removed: exports.isAPIDataMessage = */ void isAPIDataMessage;
/* common-shake removed: exports.isPopupToggleMessage = */ void isPopupToggleMessage;
exports.ping = sendPing;
exports.onPong = onPong;
/* common-shake removed: exports.scrollToTrustBox = */ void scrollToTrustBox;

},{"./utils.js":26}],10:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
var styleAlignmentPositions = exports.styleAlignmentPositions = ['left', 'right'];

},{}],13:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.hasProductReviews = exports.hasServiceReviewsMultiFetch = exports.hasServiceReviews = /* common-shake removed: exports.constructTrustBoxAndComplete = */ exports.multiFetchData = exports.fetchData = undefined;

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _call = require('../../api/call');

var _utils = require('../../utils');

var _loader = require('../templates/loader');

var _errorFallback = require('../templates/errorFallback');

var _communication = require('../../communication');

var _fn = require('../../fn');

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _objectWithoutProperties(obj, keys) { var target = {}; for (var i in obj) { if (keys.indexOf(i) >= 0) continue; if (!Object.prototype.hasOwnProperty.call(obj, i)) continue; target[i] = obj[i]; } return target; }

/**
 * Define a unique single fetch object key, allowing us to flatten back to a
 * single set of base data. This is arbitrary, and has been selected to ensure
 * it will not be accidentally used in a fetchParamsObject.
 */
var singleFetchObjectKey = 'default_singleFetch_f98ac77b';

/**
 * Flatten a fetchParamsObject value to one single set of fetchParams, where
 * that object contains only one value, and it is indexed by
 * singleFetchObjectKey.
 */
var flattenSingleParams = function flattenSingleParams(fetchParamsObject) {
  var keys = Object.keys(fetchParamsObject);
  return singleFetchObjectKey in fetchParamsObject && keys.length === 1 ? fetchParamsObject[singleFetchObjectKey] : fetchParamsObject;
};

/**
 * Check if business has service reviews
 */
var hasServiceReviews = function hasServiceReviews(_ref) {
  var total = _ref.businessEntity.numberOfReviews.total;
  return total > 0;
};

/**
 * Check if a business has service reviews using multi-fetch.
 *
 * This checks that any of the base data sets has service reviews present
 * within it.
 */
var hasServiceReviewsMultiFetch = function hasServiceReviewsMultiFetch(baseData) {
  var keys = Object.keys(baseData);
  return keys.some(function (k) {
    return hasServiceReviews(baseData[k]);
  });
};

/**
 * Check if business has imported or regular product reviews
 */
var hasProductReviews = function hasProductReviews(_ref2) {
  var productReviewsSummary = _ref2.productReviewsSummary,
      importedProductReviewsSummary = _ref2.importedProductReviewsSummary;

  var totalProductReviews = productReviewsSummary ? productReviewsSummary.numberOfReviews.total : 0;
  var totalImportedProductReviews = importedProductReviewsSummary ? importedProductReviewsSummary.numberOfReviews.total : 0;

  return totalProductReviews + totalImportedProductReviews > 0;
};

// Construct a base data call promise.
var baseDataCall = function baseDataCall(uri) {
  return function (_ref3) {
    var businessUnitId = _ref3.businessUnitId,
        locale = _ref3.locale,
        opts = _objectWithoutProperties(_ref3, ['businessUnitId', 'locale']);

    var baseDataParams = (0, _fn.rejectNullaryValues)(_extends({
      businessUnitId: businessUnitId,
      locale: locale
    }, opts, {
      theme: null // Force rejection of the theme param
    }));
    return (0, _call.apiCall)(uri, baseDataParams);
  };
};

/**
 * Call a constructTrustBox callback, and then complete the loading process
 * for the TrustBox.
 */
var constructTrustBoxAndComplete = function constructTrustBoxAndComplete(constructTrustBox) {
  var passToPopup = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  var hasReviewsFromBaseData = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : hasServiceReviews;
  return function (_ref4) {
    var baseData = _ref4.baseData,
        locale = _ref4.locale,
        theme = _ref4.theme,
        hasMoreReviews = _ref4.hasMoreReviews,
        loadMoreReviews = _ref4.loadMoreReviews;

    var hasReviews = hasReviewsFromBaseData(baseData);

    constructTrustBox({
      baseData: baseData,
      locale: locale,
      hasMoreReviews: hasMoreReviews,
      loadMoreReviews: loadMoreReviews
    });

    // Conditionally send to popup
    var sendOnPopupLoad = function sendOnPopupLoad(_ref5) {
      var event = _ref5.data;

      if ((0, _communication.isLoadedMessage)(event)) {
        (0, _communication.sendAPIDataMessage)({
          baseData: baseData,
          locale: locale
        });
      }
    };
    if (passToPopup) {
      (0, _communication.setListener)(sendOnPopupLoad);
    }

    (0, _utils.showTrustBox)(theme, hasReviews);
    (0, _errorFallback.removeErrorFallback)();
  };
};

/**
 * Fetch data from the data API, making zero or more requests.
 *
 * This function accepts an object with arbitrary keys, and values which are
 * each an object containing query params for one request. A request is made
 * for each query param object, and the result is wrapped within an object
 * indexed by the keys of the original argument object.
 *
 * These data, together with locale data, are passed to the
 * constructTrustBox callback.
 *
 * An optional argument, passToPopup, can be provided to this function. If set
 * to a truthy value, this function will attempt to pass the data obtained to
 * any popup iframe.
 */
var multiFetchData = function multiFetchData(uri) {
  return function (fetchParamsObject, constructTrustBox, passToPopup, hasReviewsFromBaseData) {
    var firstFetchParams = fetchParamsObject[Object.keys(fetchParamsObject)[0]];
    var locale = firstFetchParams.locale,
        _firstFetchParams$the = firstFetchParams.theme,
        theme = _firstFetchParams$the === undefined ? 'light' : _firstFetchParams$the;


    var baseDataPromises = (0, _fn.promiseAllObject)((0, _fn.mapObject)(baseDataCall(uri), fetchParamsObject));
    var readyPromise = (0, _utils.getOnPageReady)();

    // eslint-disable-next-line compat/compat
    var fetchPromise = Promise.all([baseDataPromises, readyPromise]).then(function (_ref6) {
      var _ref7 = _slicedToArray(_ref6, 1),
          originalBaseData = _ref7[0];

      var baseData = flattenSingleParams(originalBaseData);

      return {
        baseData: baseData,
        locale: locale,
        theme: theme
      };
    }).then(constructTrustBoxAndComplete(constructTrustBox, passToPopup, hasReviewsFromBaseData)).catch(function (e) {
      if (e && e.FallbackLogo) {
        // render fallback only if allowed, based on the response
        return (0, _errorFallback.errorFallback)();
      }
      // do nothing
    });

    (0, _loader.withLoader)(fetchPromise);
  };
};

// Fetch and structure API data.
var fetchData = function fetchData(uri) {
  return function (fetchParams, constructTrustBox, passToPopup, hasReviewsFromBaseData) {
    var fetchParamsObject = _defineProperty({}, singleFetchObjectKey, fetchParams);
    multiFetchData(uri)(fetchParamsObject, constructTrustBox, passToPopup, hasReviewsFromBaseData);
  };
};

exports.fetchData = fetchData;
exports.multiFetchData = multiFetchData;
/* common-shake removed: exports.constructTrustBoxAndComplete = */ void constructTrustBoxAndComplete;
exports.hasServiceReviews = hasServiceReviews;
exports.hasServiceReviewsMultiFetch = hasServiceReviewsMultiFetch;
exports.hasProductReviews = hasProductReviews;

},{"../../api/call":2,"../../communication":6,"../../fn":8,"../../utils":26,"../templates/errorFallback":20,"../templates/loader":21}],20:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.removeErrorFallback = exports.errorFallback = undefined;

var _dom = require('../../dom');

var _templating = require('../templating');

var _utils = require('../../utils');

var _logo = require('../assets/logo');

var errorFallback = function errorFallback() {
  var containerElement = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'tp-widget-fallback';

  var container = document.getElementById(containerElement);

  (0, _dom.populateElements)([{
    element: container,
    string: (0, _templating.a)({
      href: 'https://www.trustpilot.com?utm_medium=trustboxfallback',
      target: '_blank',
      rel: 'noopener noreferrer'
    }, (0, _templating.mkElemWithSvg)(_logo.logo, 'fallback-logo'))
  }]);
};

var removeErrorFallback = function removeErrorFallback() {
  var containerElement = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'tp-widget-fallback';

  var container = document.getElementById(containerElement);
  (0, _utils.removeElement)(container);
};

exports.errorFallback = errorFallback;
exports.removeErrorFallback = removeErrorFallback;

},{"../../dom":7,"../../utils":26,"../assets/logo":17,"../templating":24}],21:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.withLoader = undefined;

var _dom = require('../../dom');

var _utils = require('../../utils');

var _templating = require('../templating');

var _logo = require('../assets/logo');

var defaultLoaderContainer = 'tp-widget-loader';

var addLoader = function addLoader(loaderElement) {
  var loader = document.getElementById(loaderElement);

  (0, _dom.populateElements)([{
    element: loader,
    string: (0, _templating.mkElemWithSvg)(_logo.logo)
  }]);
};

var removeLoader = function removeLoader(loaderElement) {
  var loader = document.getElementById(loaderElement);
  var loaderLoadedClass = loaderElement + '--loaded';
  (0, _dom.addClass)(loader, loaderLoadedClass);

  // Remove loader after completion of animation.
  if (loader) {
    loader.addEventListener('animationend', function () {
      return (0, _utils.removeElement)(loader);
    });
    loader.addEventListener('webkitAnimationEnd', function () {
      return (0, _utils.removeElement)(loader);
    });
    loader.addEventListener('oanimationend', function () {
      return (0, _utils.removeElement)(loader);
    });
  }
};

// Creates a loader element in the DOM, then resolves a passed promise and removes
// the loader once complete. The loader is displayed only after the passed delay
// has elapsed.
var withLoader = function withLoader(promise) {
  var _ref = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {},
      _ref$loaderElement = _ref.loaderElement,
      loaderElement = _ref$loaderElement === undefined ? defaultLoaderContainer : _ref$loaderElement,
      _ref$delay = _ref.delay,
      delay = _ref$delay === undefined ? 1000 : _ref$delay;

  var loaderTimeoutId = setTimeout(function () {
    return addLoader(loaderElement);
  }, delay);
  return promise.finally(function () {
    clearTimeout(loaderTimeoutId);
    removeLoader(loaderElement);
  });
};

exports.withLoader = withLoader;

},{"../../dom":7,"../../utils":26,"../assets/logo":17,"../templating":24}],15:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
/* common-shake removed: exports.fetchProductReview = */ /* common-shake removed: exports.fetchProductData = */ undefined;

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _fetchData = require('./fetchData');

var _call = require('../../api/call');

var _reviewFetcher = require('../../api/reviewFetcher');

var _reviewFetcher2 = _interopRequireDefault(_reviewFetcher);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _objectWithoutProperties(obj, keys) { var target = {}; for (var i in obj) { if (keys.indexOf(i) >= 0) continue; if (!Object.prototype.hasOwnProperty.call(obj, i)) continue; target[i] = obj[i]; } return target; }

/**
 * Fetches data for a product attribute TrustBox.
 *
 * This uses a "new-style" endpoint, which takes a templateId and supplies data
 * based on that.
 */
var fetchProductData = function fetchProductData(templateId) {
  return function (fetchParams, constructTrustBox) {
    var passToPopup = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
    var includeImportedReviews = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : false;

    // Add extra data to the constructTrustBox callback, where we are fetching reviews
    var wrappedConstruct = function wrappedConstruct(_ref) {
      var baseData = _ref.baseData,
          locale = _ref.locale,
          args = _objectWithoutProperties(_ref, ['baseData', 'locale']);

      var fetcher = new _reviewFetcher2.default(_extends({
        baseData: baseData,
        includeImportedReviews: includeImportedReviews,
        reviewsPerPage: parseInt(fetchParams.reviewsPerPage),
        locale: locale
      }, args));
      return fetcher.consumeReviews(constructTrustBox)();
    };

    var construct = fetchParams.reviewsPerPage > 0 ? wrappedConstruct : constructTrustBox;
    (0, _fetchData.fetchData)('/trustbox-data/' + templateId)(fetchParams, construct, passToPopup, _fetchData.hasProductReviews);
  };
};

/**
 * Fetches product review data given an ID and a locale.
 */
var fetchProductReview = function fetchProductReview(productReviewId, locale, callback) {
  (0, _call.apiCall)('/product-reviews/' + productReviewId, { locale: locale }).then(callback);
};

/* common-shake removed: exports.fetchProductData = */ void fetchProductData;
/* common-shake removed: exports.fetchProductReview = */ void fetchProductReview;

},{"../../api/call":2,"../../api/reviewFetcher":3,"./fetchData":13}],16:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
/* common-shake removed: exports.SCALE_DIMENSIONS_105x19 = */ /* common-shake removed: exports.SCALE_DIMENSIONS_90x16 = */ /* common-shake removed: exports.SCALE_DIMENSIONS_80x15 = */ exports.svgStarStyle = exports.wrapSvg = undefined;

var _utils = require('../../utils');

/*
 * IE11 does not properly display SVG tags, except using one of several hacks.
 * So, we use one below: we wrap each SVG in a div element, with particular
 * styling attached. We do this following Option 4 in the article at
 * https://css-tricks.com/scale-svg/.
 */

var wrapSvg = function wrapSvg(dimensions, inner) {
  var props = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

  var sanitizedProps = Object.keys(props).reduce(function (acc, cur) {
    acc[cur] = (0, _utils.sanitizeHtmlProp)(props[cur]);
    if (cur === 'color') {
      acc[cur] = (0, _utils.sanitizeColor)(acc[cur]);
    }
    return acc;
  }, {});
  return '\n    <div style="position: relative; height: 0; width: 100%; padding: 0; padding-bottom: ' + dimensions.height / dimensions.width * 100 + '%;">\n      ' + inner(dimensions, sanitizedProps) + '\n    </div>\n  ';
};

var svgStarStyle = 'style="position: absolute; height: 100%; width: 100%; left: 0; top: 0;"';

var SCALE_DIMENSIONS_80x15 = '80x15';
var SCALE_DIMENSIONS_90x16 = '90x16';
var SCALE_DIMENSIONS_105x19 = '105x19';

exports.wrapSvg = wrapSvg;
exports.svgStarStyle = svgStarStyle;
/* common-shake removed: exports.SCALE_DIMENSIONS_80x15 = */ void SCALE_DIMENSIONS_80x15;
/* common-shake removed: exports.SCALE_DIMENSIONS_90x16 = */ void SCALE_DIMENSIONS_90x16;
/* common-shake removed: exports.SCALE_DIMENSIONS_105x19 = */ void SCALE_DIMENSIONS_105x19;

},{"../../utils":26}],17:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.logo = undefined;

var _helpers = require('./helpers');

var icon = function icon(dimensions) {
  var titleId = 'trustpilotLogo-' + Math.random().toString(36).substring(2);

  return '\n    <svg role="img" viewBox="0 0 ' + dimensions.width + ' ' + dimensions.height + '" aria-labelledby="' + titleId + '"  width="' + dimensions.width + '" height="' + dimensions.height + '" xmlns="http://www.w3.org/2000/svg" ' + _helpers.svgStarStyle + '>\n      <title id="' + titleId + '">Trustpilot</title>\n      <path class="tp-logo__text" d="M33.074774 11.07005H45.81806v2.364196h-5.010656v13.290316h-2.755306V13.434246h-4.988435V11.07005h.01111zm12.198892 4.319629h2.355341v2.187433h.04444c.077771-.309334.222203-.60762.433295-.894859.211092-.287239.466624-.56343.766597-.79543.299972-.243048.633276-.430858.999909-.585525.366633-.14362.744377-.220953 1.12212-.220953.288863 0 .499955.011047.611056.022095.1111.011048.222202.033143.344413.04419v2.408387c-.177762-.033143-.355523-.055238-.544395-.077333-.188872-.022096-.366633-.033143-.544395-.033143-.422184 0-.822148.08838-1.199891.254096-.377744.165714-.699936.41981-.977689.740192-.277753.331429-.499955.729144-.666606 1.21524-.166652.486097-.244422 1.03848-.244422 1.668195v5.39125h-2.510883V15.38968h.01111zm18.220567 11.334883H61.02779v-1.579813h-.04444c-.311083.574477-.766597 1.02743-1.377653 1.369908-.611055.342477-1.233221.51924-1.866497.51924-1.499864 0-2.588654-.364573-3.25526-1.104765-.666606-.740193-.999909-1.856005-.999909-3.347437V15.38968h2.510883v6.948968c0 .994288.188872 1.701337.577725 2.1101.377744.408763.922139.618668 1.610965.618668.533285 0 .96658-.077333 1.322102-.243048.355524-.165714.644386-.37562.855478-.65181.222202-.265144.377744-.596574.477735-.972194.09999-.37562.144431-.784382.144431-1.226288v-6.573349h2.510883v11.323836zm4.27739-3.634675c.07777.729144.355522 1.237336.833257 1.535623.488844.287238 1.06657.441905 1.744286.441905.233312 0 .499954-.022095.799927-.055238.299973-.033143.588836-.110476.844368-.209905.266642-.099429.477734-.254096.655496-.452954.166652-.198857.244422-.452953.233312-.773335-.01111-.320381-.133321-.585525-.355523-.784382-.222202-.209906-.499955-.364573-.844368-.497144-.344413-.121525-.733267-.232-1.17767-.320382-.444405-.088381-.888809-.18781-1.344323-.287239-.466624-.099429-.922138-.232-1.355432-.37562-.433294-.14362-.822148-.342477-1.166561-.596573-.344413-.243048-.622166-.56343-.822148-.950097-.211092-.386668-.311083-.861716-.311083-1.436194 0-.618668.155542-1.12686.455515-1.54667.299972-.41981.688826-.75124 1.14434-1.005336.466624-.254095.97769-.430858 1.544304-.541334.566615-.099429 1.11101-.154667 1.622075-.154667.588836 0 1.15545.066286 1.688736.18781.533285.121524 1.02213.320381 1.455423.60762.433294.276191.788817.640764 1.07768 1.08267.288863.441905.466624.98324.544395 1.612955h-2.621984c-.122211-.596572-.388854-1.005335-.822148-1.204193-.433294-.209905-.933248-.309334-1.488753-.309334-.177762 0-.388854.011048-.633276.04419-.244422.033144-.466624.088382-.688826.165715-.211092.077334-.388854.198858-.544395.353525-.144432.154667-.222203.353525-.222203.60762 0 .309335.111101.552383.322193.740193.211092.18781.488845.342477.833258.475048.344413.121524.733267.232 1.177671.320382.444404.088381.899918.18781 1.366542.287239.455515.099429.899919.232 1.344323.37562.444404.14362.833257.342477 1.17767.596573.344414.254095.622166.56343.833258.93905.211092.37562.322193.850668.322193 1.40305 0 .673906-.155541 1.237336-.466624 1.712385-.311083.464001-.711047.850669-1.199891 1.137907-.488845.28724-1.04435.508192-1.644295.640764-.599946.132572-1.199891.198857-1.788727.198857-.722156 0-1.388762-.077333-1.999818-.243048-.611056-.165714-1.14434-.408763-1.588745-.729144-.444404-.33143-.799927-.740192-1.05546-1.226289-.255532-.486096-.388853-1.071621-.411073-1.745528h2.533103v-.022095zm8.288135-7.700208h1.899828v-3.402675h2.510883v3.402675h2.26646v1.867052h-2.26646v6.054109c0 .265143.01111.486096.03333.684954.02222.18781.07777.353524.155542.486096.07777.132572.199981.232.366633.298287.166651.066285.377743.099428.666606.099428.177762 0 .355523 0 .533285-.011047.177762-.011048.355523-.033143.533285-.077334v1.933338c-.277753.033143-.555505.055238-.811038.088381-.266642.033143-.533285.04419-.811037.04419-.666606 0-1.199891-.066285-1.599855-.18781-.399963-.121523-.722156-.309333-.944358-.552381-.233313-.243049-.377744-.541335-.466625-.905907-.07777-.364573-.13332-.784383-.144431-1.248384v-6.683825h-1.899827v-1.889147h-.02222zm8.454788 0h2.377562V16.9253h.04444c.355523-.662858.844368-1.12686 1.477644-1.414098.633276-.287239 1.310992-.430858 2.055369-.430858.899918 0 1.677625.154667 2.344231.475048.666606.309335 1.222111.740193 1.666515 1.292575.444405.552382.766597 1.193145.9888 1.92229.222202.729145.333303 1.513527.333303 2.3421 0 .762288-.099991 1.50248-.299973 2.20953-.199982.718096-.499955 1.347812-.899918 1.900194-.399964.552383-.911029.98324-1.533194 1.31467-.622166.33143-1.344323.497144-2.18869.497144-.366634 0-.733267-.033143-1.0999-.099429-.366634-.066286-.722157-.176762-1.05546-.320381-.333303-.14362-.655496-.33143-.933249-.56343-.288863-.232-.522175-.497144-.722157-.79543h-.04444v5.656393h-2.510883V15.38968zm8.77698 5.67849c0-.508193-.06666-1.005337-.199981-1.491433-.133321-.486096-.333303-.905907-.599946-1.281527-.266642-.37562-.599945-.673906-.988799-.894859-.399963-.220953-.855478-.342477-1.366542-.342477-1.05546 0-1.855387.364572-2.388672 1.093717-.533285.729144-.799928 1.701337-.799928 2.916578 0 .574478.066661 1.104764.211092 1.59086.144432.486097.344414.905908.633276 1.259432.277753.353525.611056.629716.99991.828574.388853.209905.844367.309334 1.355432.309334.577725 0 1.05546-.121524 1.455423-.353525.399964-.232.722157-.541335.97769-.905907.255531-.37562.444403-.79543.555504-1.270479.099991-.475049.155542-.961145.155542-1.458289zm4.432931-9.99812h2.510883v2.364197h-2.510883V11.07005zm0 4.31963h2.510883v11.334883h-2.510883V15.389679zm4.755124-4.31963h2.510883v15.654513h-2.510883V11.07005zm10.210184 15.963847c-.911029 0-1.722066-.154667-2.433113-.452953-.711046-.298287-1.310992-.718097-1.810946-1.237337-.488845-.530287-.866588-1.160002-1.12212-1.889147-.255533-.729144-.388854-1.535622-.388854-2.408386 0-.861716.133321-1.657147.388853-2.386291.255533-.729145.633276-1.35886 1.12212-1.889148.488845-.530287 1.0999-.93905 1.810947-1.237336.711047-.298286 1.522084-.452953 2.433113-.452953.911028 0 1.722066.154667 2.433112.452953.711047.298287 1.310992.718097 1.810947 1.237336.488844.530287.866588 1.160003 1.12212 1.889148.255532.729144.388854 1.524575.388854 2.38629 0 .872765-.133322 1.679243-.388854 2.408387-.255532.729145-.633276 1.35886-1.12212 1.889147-.488845.530287-1.0999.93905-1.810947 1.237337-.711046.298286-1.522084.452953-2.433112.452953zm0-1.977528c.555505 0 1.04435-.121524 1.455423-.353525.411074-.232.744377-.541335 1.01102-.916954.266642-.37562.455513-.806478.588835-1.281527.12221-.475049.188872-.961145.188872-1.45829 0-.486096-.066661-.961144-.188872-1.44724-.122211-.486097-.322193-.905907-.588836-1.281527-.266642-.37562-.599945-.673907-1.011019-.905907-.411074-.232-.899918-.353525-1.455423-.353525-.555505 0-1.04435.121524-1.455424.353525-.411073.232-.744376.541334-1.011019.905907-.266642.37562-.455514.79543-.588835 1.281526-.122211.486097-.188872.961145-.188872 1.447242 0 .497144.06666.98324.188872 1.458289.12221.475049.322193.905907.588835 1.281527.266643.37562.599946.684954 1.01102.916954.411073.243048.899918.353525 1.455423.353525zm6.4883-9.66669h1.899827v-3.402674h2.510883v3.402675h2.26646v1.867052h-2.26646v6.054109c0 .265143.01111.486096.03333.684954.02222.18781.07777.353524.155541.486096.077771.132572.199982.232.366634.298287.166651.066285.377743.099428.666606.099428.177762 0 .355523 0 .533285-.011047.177762-.011048.355523-.033143.533285-.077334v1.933338c-.277753.033143-.555505.055238-.811038.088381-.266642.033143-.533285.04419-.811037.04419-.666606 0-1.199891-.066285-1.599855-.18781-.399963-.121523-.722156-.309333-.944358-.552381-.233313-.243049-.377744-.541335-.466625-.905907-.07777-.364573-.133321-.784383-.144431-1.248384v-6.683825h-1.899827v-1.889147h-.02222z" fill="#191919"/>\n      <path class="tp-logo__star" fill="#00B67A" d="M30.141707 11.07005H18.63164L15.076408.177071l-3.566342 10.892977L0 11.059002l9.321376 6.739063-3.566343 10.88193 9.321375-6.728016 9.310266 6.728016-3.555233-10.88193 9.310266-6.728016z"/>\n      <path class="tp-logo__star-notch" fill="#005128" d="M21.631369 20.26169l-.799928-2.463625-5.755033 4.153914z"/>\n    </svg>\n  ';
};

var logoDimensions = { width: 126, height: 31 };

var logo = exports.logo = function logo() {
  return (0, _helpers.wrapSvg)(logoDimensions, icon);
};

},{"./helpers":16}],18:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.stars = undefined;

var _helpers = require('./helpers');

var _translations = require('../translations');

var emptyStarColor = '#dcdce6';

var icon = function icon(dimensions, _ref) {
  var rating = _ref.rating,
      trustScore = _ref.trustScore,
      color = _ref.color,
      translations = _ref.translations,
      _ref$locale = _ref.locale,
      locale = _ref$locale === undefined ? _translations.defaultLocale : _ref$locale;

  var titleId = 'starRating-' + Math.random().toString(36).substring(2);
  var interpolations = { '[ratingStars]': trustScore, '[totalStars]': 5 };
  var translatedStarRating = (0, _translations.getFrameworkTranslation)('starRating', translations, interpolations);
  var formattedLocale = (0, _translations.formatLocale)(locale);

  return '\n    <svg role="img" viewBox="0 0 ' + dimensions.width + ' ' + dimensions.height + '" xmlns="http://www.w3.org/2000/svg" ' + _helpers.svgStarStyle + '>\n      <title id="' + titleId + '" lang=' + formattedLocale + '>' + translatedStarRating + '</title>\n      <g class="tp-star">\n          <path class="tp-star__canvas" fill="' + (rating >= 1 && color ? color : emptyStarColor) + '" d="M0 46.330002h46.375586V0H0z"/>\n          <path class="tp-star__shape" d="M39.533936 19.711433L13.230239 38.80065l3.838216-11.797827L7.02115 19.711433h12.418975l3.837417-11.798624 3.837418 11.798624h12.418975zM23.2785 31.510075l7.183595-1.509576 2.862114 8.800152L23.2785 31.510075z" fill="#FFF"/>\n      </g>\n      <g class="tp-star">\n          <path class="tp-star__canvas" fill="' + (rating >= 2 && color ? color : emptyStarColor) + '" d="M51.24816 46.330002h46.375587V0H51.248161z"/>\n          <path class="tp-star__canvas--half" fill="' + (rating >= 1.5 && color ? color : emptyStarColor) + '" d="M51.24816 46.330002h23.187793V0H51.248161z"/>\n          <path class="tp-star__shape" d="M74.990978 31.32991L81.150908 30 84 39l-9.660206-7.202786L64.30279 39l3.895636-11.840666L58 19.841466h12.605577L74.499595 8l3.895637 11.841466H91L74.990978 31.329909z" fill="#FFF"/>\n      </g>\n      <g class="tp-star">\n          <path class="tp-star__canvas" fill="' + (rating >= 3 && color ? color : emptyStarColor) + '" d="M102.532209 46.330002h46.375586V0h-46.375586z"/>\n          <path class="tp-star__canvas--half" fill="' + (rating >= 2.5 && color ? color : emptyStarColor) + '" d="M102.532209 46.330002h23.187793V0h-23.187793z"/>\n          <path class="tp-star__shape" d="M142.066994 19.711433L115.763298 38.80065l3.838215-11.797827-10.047304-7.291391h12.418975l3.837418-11.798624 3.837417 11.798624h12.418975zM125.81156 31.510075l7.183595-1.509576 2.862113 8.800152-10.045708-7.290576z" fill="#FFF"/>\n      </g>\n      <g class="tp-star">\n          <path class="tp-star__canvas" fill="' + (rating >= 4 && color ? color : emptyStarColor) + '" d="M153.815458 46.330002h46.375586V0h-46.375586z"/>\n          <path class="tp-star__canvas--half" fill="' + (rating >= 3.5 && color ? color : emptyStarColor) + '" d="M153.815458 46.330002h23.187793V0h-23.187793z"/>\n          <path class="tp-star__shape" d="M193.348355 19.711433L167.045457 38.80065l3.837417-11.797827-10.047303-7.291391h12.418974l3.837418-11.798624 3.837418 11.798624h12.418974zM177.09292 31.510075l7.183595-1.509576 2.862114 8.800152-10.045709-7.290576z" fill="#FFF"/>\n      </g>\n      <g class="tp-star">\n          <path class="tp-star__canvas" fill="' + (rating === 5 && color ? color : emptyStarColor) + '" d="M205.064416 46.330002h46.375587V0h-46.375587z"/>\n          <path class="tp-star__canvas--half" fill="' + (rating >= 4.5 && color ? color : emptyStarColor) + '" d="M205.064416 46.330002h23.187793V0h-23.187793z"/>\n          <path class="tp-star__shape" d="M244.597022 19.711433l-26.3029 19.089218 3.837419-11.797827-10.047304-7.291391h12.418974l3.837418-11.798624 3.837418 11.798624h12.418975zm-16.255436 11.798642l7.183595-1.509576 2.862114 8.800152-10.045709-7.290576z" fill="#FFF"/>\n      </g>\n    </svg>\n  ';
};

var starsDimensions = { width: 251, height: 46 };

var stars = exports.stars = function stars(props) {
  return (0, _helpers.wrapSvg)(starsDimensions, icon, props);
};

},{"../translations":25,"./helpers":16}],25:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
var defaultLocale = 'en-US';

var LOCALE_DIVIDER = '-';

var languageToCountryMap = {
  da: 'DK',
  en: 'US',
  ja: 'JP',
  nb: 'NO',
  sv: 'SE'
  // other languages assumed to have the same country code as the language code itself
};

/**
 * Tries to find the country for the given language.
 * If no country is found, the language itself is returned.
 * Acts as a safety mechanism for translations when only the language part is present in the given locale.
 *
 * @param {string} language the language for which the country should be found.
 */
var tryGetCountryForLanguage = function tryGetCountryForLanguage(language) {
  var country = languageToCountryMap[language] || language;
  return country;
};

var formatLocale = function formatLocale(locale) {
  if (!locale) return defaultLocale;
  var localeParts = locale.split(LOCALE_DIVIDER);
  var language = localeParts[0];
  var country = localeParts[1];

  if (!country) {
    country = tryGetCountryForLanguage(language);
  }

  return language && country ? '' + language + LOCALE_DIVIDER + country.toUpperCase() : defaultLocale;
};

var lookupTranslation = function lookupTranslation(keyParts, translationTable) {
  return keyParts.reduce(function (a, b) {
    return a && a[b] ? a[b] : '';
  }, translationTable || {});
};

var getRawTranslation = function getRawTranslation(key, translationTable) {
  var keyParts = key.split('.');
  return lookupTranslation(keyParts, translationTable);
};

var getFrameworkTranslation = function getFrameworkTranslation(key, translationTable) {
  var interpolations = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  var links = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : [];

  var rawTranslation = getRawTranslation(key, translationTable);
  var translation = Object.keys(interpolations).reduce(function (value, key) {
    return value.replace(key, interpolations[key]);
  }, rawTranslation);
  var translationWithLinksReplaced = links.reduce(function (previous, current) {
    return previous.replace('[LINK-END]', '</a>').replace('[LINK-BEGIN]', current);
  }, translation);

  return translationWithLinksReplaced;
};

exports.defaultLocale = defaultLocale;
exports.formatLocale = formatLocale;
exports.getFrameworkTranslation = getFrameworkTranslation;

},{}],24:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});
/* common-shake removed: exports.customElement = */ exports.mkElemWithSvg = /* common-shake removed: exports.object = */ /* common-shake removed: exports.span = */ /* common-shake removed: exports.input = */ /* common-shake removed: exports.label = */ /* common-shake removed: exports.img = */ exports.div = exports.a = undefined;

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _utils = require('../utils');

function _objectWithoutProperties(obj, keys) { var target = {}; for (var i in obj) { if (keys.indexOf(i) >= 0) continue; if (!Object.prototype.hasOwnProperty.call(obj, i)) continue; target[i] = obj[i]; } return target; }

var flatten = function flatten(arrs) {
  return [].concat.apply([], arrs);
};

var mkProps = function mkProps(props) {
  return Object.keys(props).map(function (key) {
    var sanitizedProp = (0, _utils.sanitizeHtmlProp)(props[key]);
    return key + '="' + sanitizedProp + '"';
  }).join(' ');
};

var mkElem = function mkElem(tag) {
  return function (props) {
    for (var _len = arguments.length, children = Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
      children[_key - 1] = arguments[_key];
    }

    return '<' + tag + ' ' + mkProps(props) + '>' + flatten(children).join('\n') + '</' + tag + '>';
  };
};

var mkNonClosingElem = function mkNonClosingElem(tag) {
  return function (props) {
    return '<' + tag + ' ' + mkProps(props) + '>';
  };
};

var a = mkElem('a');
var div = mkElem('div');
var img = mkElem('img');
var label = mkElem('label');
var span = mkElem('span');
var input = mkNonClosingElem('input');
var object = mkElem('object');
var customElement = mkElem;

var mkElemWithSvg = function mkElemWithSvg(svg) {
  var className = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
  var props = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

  var ariaHideSvg = props.ariaHideSvg,
      otherProps = _objectWithoutProperties(props, ['ariaHideSvg']);

  var ariaHidden = ariaHideSvg ? { 'aria-hidden': 'true' } : {};
  return div(_extends({ class: className }, ariaHidden), svg(otherProps));
};

exports.a = a;
exports.div = div;
/* common-shake removed: exports.img = */ void img;
/* common-shake removed: exports.label = */ void label;
/* common-shake removed: exports.input = */ void input;
/* common-shake removed: exports.span = */ void span;
/* common-shake removed: exports.object = */ void object;
exports.mkElemWithSvg = mkElemWithSvg;
/* common-shake removed: exports.customElement = */ void customElement;

},{"../utils":26}]},{},[1])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJhcHAvanMvbWFpbi5qcyIsIm5vZGVfbW9kdWxlcy9AdHJ1c3RwaWxvdC90cnVzdGJveC1mcmFtZXdvcmstdmFuaWxsYS9tb2R1bGVzL2RvbS5qcyIsIm5vZGVfbW9kdWxlcy9AdHJ1c3RwaWxvdC90cnVzdGJveC1mcmFtZXdvcmstdmFuaWxsYS9tb2R1bGVzL2ltcHJlc3Npb24uanMiLCJub2RlX21vZHVsZXMvQHRydXN0cGlsb3QvdHJ1c3Rib3gtZnJhbWV3b3JrLXZhbmlsbGEvbW9kdWxlcy9xdWVyeVN0cmluZy5qcyIsIm5vZGVfbW9kdWxlcy9AdHJ1c3RwaWxvdC90cnVzdGJveC1mcmFtZXdvcmstdmFuaWxsYS9tb2R1bGVzL3V0aWxzLmpzIiwibm9kZV9tb2R1bGVzL0B0cnVzdHBpbG90L3RydXN0Ym94LWZyYW1ld29yay12YW5pbGxhL21vZHVsZXMvc2xpbS9pbml0LmpzIiwibm9kZV9tb2R1bGVzL0B0cnVzdHBpbG90L3RydXN0Ym94LWZyYW1ld29yay12YW5pbGxhL21vZHVsZXMvc2xpbS90ZW1wbGF0ZXMvc3RhcnMuanMiLCJub2RlX21vZHVsZXMvQHRydXN0cGlsb3QvdHJ1c3Rib3gtZnJhbWV3b3JrLXZhbmlsbGEvbW9kdWxlcy9zbGltL3RlbXBsYXRlcy9sb2dvLmpzIiwibm9kZV9tb2R1bGVzL0B0cnVzdHBpbG90L3RydXN0Ym94LWZyYW1ld29yay12YW5pbGxhL21vZHVsZXMvc2xpbS9hcGkvaW5kZXguanMiLCJub2RlX21vZHVsZXMvQHRydXN0cGlsb3QvdHJ1c3Rib3gtZnJhbWV3b3JrLXZhbmlsbGEvbW9kdWxlcy9hcGkvY2FsbC5qcyIsIm5vZGVfbW9kdWxlcy9AdHJ1c3RwaWxvdC90cnVzdGJveC1mcmFtZXdvcmstdmFuaWxsYS9tb2R1bGVzL3hoci5qcyIsIm5vZGVfbW9kdWxlcy9AdHJ1c3RwaWxvdC90cnVzdGJveC1mcmFtZXdvcmstdmFuaWxsYS9tb2R1bGVzL3Jvb3RVcmkuanMiLCJub2RlX21vZHVsZXMvQHRydXN0cGlsb3QvdHJ1c3Rib3gtZnJhbWV3b3JrLXZhbmlsbGEvbW9kdWxlcy9hcGkvcmV2aWV3RmV0Y2hlci9pbmRleC5qcyIsIm5vZGVfbW9kdWxlcy9AdHJ1c3RwaWxvdC90cnVzdGJveC1mcmFtZXdvcmstdmFuaWxsYS9tb2R1bGVzL2ZuLmpzIiwibm9kZV9tb2R1bGVzL0B0cnVzdHBpbG90L3RydXN0Ym94LWZyYW1ld29yay12YW5pbGxhL21vZHVsZXMvYXBpL3Jldmlld0ZldGNoZXIvdXRpbC5qcyIsIm5vZGVfbW9kdWxlcy9AdHJ1c3RwaWxvdC90cnVzdGJveC1mcmFtZXdvcmstdmFuaWxsYS9tb2R1bGVzL2FwaS9yZXZpZXdGZXRjaGVyL3Jlc3BvbnNlUHJvY2Vzc29yLmpzIiwibm9kZV9tb2R1bGVzL0B0cnVzdHBpbG90L3RydXN0Ym94LWZyYW1ld29yay12YW5pbGxhL21vZHVsZXMvY29tbXVuaWNhdGlvbi5qcyIsIm5vZGVfbW9kdWxlcy9AdHJ1c3RwaWxvdC90cnVzdGJveC1mcmFtZXdvcmstdmFuaWxsYS9tb2R1bGVzL21vZGVscy9zdHlsZUFsaWdubWVudFBvc2l0aW9ucy5qcyIsIm5vZGVfbW9kdWxlcy9AdHJ1c3RwaWxvdC90cnVzdGJveC1mcmFtZXdvcmstdmFuaWxsYS9tb2R1bGVzL3NsaW0vYXBpL2ZldGNoRGF0YS5qcyIsIm5vZGVfbW9kdWxlcy9AdHJ1c3RwaWxvdC90cnVzdGJveC1mcmFtZXdvcmstdmFuaWxsYS9tb2R1bGVzL3NsaW0vdGVtcGxhdGVzL2Vycm9yRmFsbGJhY2suanMiLCJub2RlX21vZHVsZXMvQHRydXN0cGlsb3QvdHJ1c3Rib3gtZnJhbWV3b3JrLXZhbmlsbGEvbW9kdWxlcy9zbGltL3RlbXBsYXRlcy9sb2FkZXIuanMiLCJub2RlX21vZHVsZXMvQHRydXN0cGlsb3QvdHJ1c3Rib3gtZnJhbWV3b3JrLXZhbmlsbGEvbW9kdWxlcy9zbGltL2FwaS9wcm9kdWN0UmV2aWV3cy5qcyIsIm5vZGVfbW9kdWxlcy9AdHJ1c3RwaWxvdC90cnVzdGJveC1mcmFtZXdvcmstdmFuaWxsYS9tb2R1bGVzL3NsaW0vYXNzZXRzL2hlbHBlcnMuanMiLCJub2RlX21vZHVsZXMvQHRydXN0cGlsb3QvdHJ1c3Rib3gtZnJhbWV3b3JrLXZhbmlsbGEvbW9kdWxlcy9zbGltL2Fzc2V0cy9sb2dvLmpzIiwibm9kZV9tb2R1bGVzL0B0cnVzdHBpbG90L3RydXN0Ym94LWZyYW1ld29yay12YW5pbGxhL21vZHVsZXMvc2xpbS9hc3NldHMvc3RhcnMuanMiLCJub2RlX21vZHVsZXMvQHRydXN0cGlsb3QvdHJ1c3Rib3gtZnJhbWV3b3JrLXZhbmlsbGEvbW9kdWxlcy9zbGltL3RyYW5zbGF0aW9ucy5qcyIsIm5vZGVfbW9kdWxlcy9AdHJ1c3RwaWxvdC90cnVzdGJveC1mcmFtZXdvcmstdmFuaWxsYS9tb2R1bGVzL3NsaW0vdGVtcGxhdGluZy5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBLFlBQUEsQ0FBQTs7QUFFQSxJQUFBLFdBQUEsR0FBQSxPQUFBLENBQUEsMkRBQUEsQ0FBQSxDQUFBOzs7O0FBQ0EsSUFBQSxJQUFBLEdBQUEsT0FBQSxDQUFBLHlEQUFBLENBQUEsQ0FBQTs7QUFDQSxJQUFBLE1BQUEsR0FBQSxPQUFBLENBQUEsc0RBQUEsQ0FBQSxDQUFBOztBQVNBLElBQUEsWUFBQSxHQUFBLE9BQUEsQ0FBQSw0REFBQSxDQUFBLENBQUE7O0FBQ0EsSUFBQSxNQUFBLEdBQUEsT0FBQSxDQUFBLHFFQUFBLENBQUEsQ0FBQTs7QUFDQSxJQUFBLEtBQUEsR0FBQSxPQUFBLENBQUEsb0VBQUEsQ0FBQSxDQUFBOztBQUNBLElBQUEsSUFBQSxHQUFBLE9BQUEsQ0FBQSxvREFBQSxDQUFBLENBQUE7O0FBQ0EsSUFBQSxLQUFBLEdBQUEsT0FBQSxDQUFBLDBEQUFBLENBQUEsQ0FBQTs7Ozs7O0FBRUEsWUFBQSxDQUFBLE9BQUEsQ0FBUyx1QkFBVCxFQUFBLENBQUE7O0FBRUEsSUFBTSxNQUFBLEdBQVMsQ0FBQSxD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LFNBQUEsQ0FBQSxDQUFBLENBQUEsS0FBQSxTQUFBLEdBQUEsU0FBQSxDQUFBLENBQUEsQ0FBQSxHQUFQLEVBQU8sQ0FBQTs7RUFBQSxJQUNqRCxXQURpRCxHQUNsQixLQURrQixDQUNqRCxXQURpRDtNQUNqQyxVQURpQyxHQUFBLHdCQUFBLENBQ2xCLEtBRGtCLEVBQUEsQ0FBQSxhQUFBLENBQUEsQ0FBQSxDQUFBOztFQUV6RCxJQUFNLFVBQUEsR0FBYSxXQUFBLEdBQWMsRUFBRSxhQUFBLEVBQWUsTUFBakIsRUFBZCxHQUEwQyxFQUE3RCxDQUFBO0VBQ0EsT0FBTyxHQUFBLENBQUEsUUFBQSxDQUFBLEVBQU0sS0FBQSxFQUFPLFNBQWIsRUFBQSxFQUEyQixVQUEzQixDQUFBLEVBQXlDLEdBQUEsQ0FBSSxVQUFKLENBQXpDLENBQVAsQ0FBQTtDQUhGLENBQUE7O1FBTVMsSUFBQTtRQUFHLE1BQUE7K0NBQUs7aURBQUs7aURBQU87Z0RBQU87a0RBQU07UUFBUSxnQkFBQTt5REFBZSIsImZpbGUiOiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uKCl7ZnVuY3Rpb24gcihlLG4sdCl7ZnVuY3Rpb24gbyhpLGYpe2lmKCFuW2ldKXtpZighZVtpXSl7dmFyIGM9XCJmdW5jdGlvblwiPT10eXBlb2YgcmVxdWlyZSYmcmVxdWlyZTtpZighZiYmYylyZXR1cm4gYyhpLCEwKTtpZih1KXJldHVybiB1KGksITApO3ZhciBhPW5ldyBFcnJvcihcIkNhbm5vdCBmaW5kIG1vZHVsZSAnXCIraStcIidcIik7dGhyb3cgYS5jb2RlPVwiTU9EVUxFX05PVF9GT1VORFwiLGF9dmFyIHA9bltpXT17ZXhwb3J0czp7fX07ZVtpXVswXS5jYWxsKHAuZXhwb3J0cyxmdW5jdGlvbihyKXt2YXIgbj1lW2ldWzFdW3JdO3JldHVybiBvKG58fHIpfSxwLHAuZXhwb3J0cyxyLGUsbix0KX1yZXR1cm4gbltpXS5leHBvcnRzfWZvcih2YXIgdT1cImZ1bmN0aW9uXCI9PXR5cGVvZiByZXF1aXJlJiZyZXF1aXJlLGk9MDtpPHQubGVuZ3RoO2krKylvKHRbaV0pO3JldHVybiBvfXJldHVybiByfSkoKSIsIid1c2Ugc3RyaWN0JztcblxuaW1wb3J0IHRyYWNraW5nIGZyb20gJ0B0cnVzdHBpbG90L3RydXN0Ym94LWZyYW1ld29yay12YW5pbGxhL21vZHVsZXMvaW1wcmVzc2lvbic7XG5pbXBvcnQgeyBmZXRjaFNlcnZpY2VSZXZpZXdEYXRhIH0gZnJvbSAnQHRydXN0cGlsb3QvdHJ1c3Rib3gtZnJhbWV3b3JrLXZhbmlsbGEvbW9kdWxlcy9zbGltL2FwaSc7XG5pbXBvcnQge1xuICBpbnNlcnROdW1iZXJTZXBhcmF0b3IsXG4gIHNldFRleHRDb250ZW50LFxuICBzZXRIdG1sQ29udGVudCxcbiAgYWRkVXRtUGFyYW1zLFxuICBzZXRGb250LFxuICBzZXRUZXh0Q29sb3IsXG4gIHNldEh0bWxMYW5ndWFnZSxcbn0gZnJvbSAnQHRydXN0cGlsb3QvdHJ1c3Rib3gtZnJhbWV3b3JrLXZhbmlsbGEvbW9kdWxlcy91dGlscyc7XG5pbXBvcnQgeyBnZXRBc09iamVjdCBhcyBnZXRRdWVyeVN0cmluZyB9IGZyb20gJ0B0cnVzdHBpbG90L3RydXN0Ym94LWZyYW1ld29yay12YW5pbGxhL21vZHVsZXMvcXVlcnlTdHJpbmcnO1xuaW1wb3J0IHsgcG9wdWxhdGVTdGFycyB9IGZyb20gJ0B0cnVzdHBpbG90L3RydXN0Ym94LWZyYW1ld29yay12YW5pbGxhL21vZHVsZXMvc2xpbS90ZW1wbGF0ZXMvc3RhcnMnO1xuaW1wb3J0IHsgcG9wdWxhdGVMb2dvIH0gZnJvbSAnQHRydXN0cGlsb3QvdHJ1c3Rib3gtZnJhbWV3b3JrLXZhbmlsbGEvbW9kdWxlcy9zbGltL3RlbXBsYXRlcy9sb2dvJztcbmltcG9ydCB7IHJlbW92ZUNsYXNzIH0gZnJvbSAnQHRydXN0cGlsb3QvdHJ1c3Rib3gtZnJhbWV3b3JrLXZhbmlsbGEvbW9kdWxlcy9kb20nO1xuaW1wb3J0IGluaXQgZnJvbSAnQHRydXN0cGlsb3QvdHJ1c3Rib3gtZnJhbWV3b3JrLXZhbmlsbGEvbW9kdWxlcy9zbGltL2luaXQnO1xuXG50cmFja2luZy5hdHRhY2hJbXByZXNzaW9uSGFuZGxlcigpO1xuXG5jb25zdCBhZGRVdG0gPSBhZGRVdG1QYXJhbXMoJ01pbmknKTtcblxuY29uc3Qge1xuICBsb2NhbGUsXG4gIGJ1c2luZXNzdW5pdElkOiBidXNpbmVzc1VuaXRJZCxcbiAgdGhlbWUgPSAnbGlnaHQnLFxuICBsb2NhdGlvbixcbiAgdGVtcGxhdGVJZCxcbiAgZm9udEZhbWlseSxcbiAgdGV4dENvbG9yLFxufSA9IGdldFF1ZXJ5U3RyaW5nKCk7XG5cbmNvbnN0IGluamVjdFdpZGdldExpbmtzID0gKHtcbiAgYmFzZURhdGE6IHtcbiAgICBidXNpbmVzc0VudGl0eToge1xuICAgICAgbnVtYmVyT2ZSZXZpZXdzOiB7IHRvdGFsOiB0b3RhbE51bWJlck9mUmV2aWV3cyB9LFxuICAgIH0sXG4gICAgbGlua3MsXG4gIH0sXG59KSA9PiB7XG4gIGNvbnN0IGl0ZW0gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncHJvZmlsZS1saW5rJyk7XG4gIGNvbnN0IGJhc2VVcmwgPSB0b3RhbE51bWJlck9mUmV2aWV3cyA/IGxpbmtzLnByb2ZpbGVVcmwgOiBsaW5rcy5ldmFsdWF0ZVVybDtcbiAgaXRlbS5ocmVmID0gYWRkVXRtKGJhc2VVcmwpO1xufTtcblxuY29uc3QgcG9wdWxhdGVOdW1iZXJPZlJldmlld3MgPSAoe1xuICBsb2NhbGUsXG4gIGJhc2VEYXRhOiB7XG4gICAgYnVzaW5lc3NFbnRpdHk6IHtcbiAgICAgIG51bWJlck9mUmV2aWV3czogeyB0b3RhbDogbnVtYmVyT2ZSZXZpZXdzIH0sXG4gICAgICB0cnVzdFNjb3JlLFxuICAgIH0sXG4gICAgdHJhbnNsYXRpb25zLFxuICB9LFxufSkgPT4ge1xuICBjb25zdCByZXZpZXdzVGV4dENvbnRhaW5lciA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cmFuc2xhdGlvbnMtcmV2aWV3cycpO1xuICBjb25zdCB0cnVzdHNjb3JlQ29udGFpbmVySWQgPSBudW1iZXJPZlJldmlld3NcbiAgICA/ICdidXNpbmVzc0VudGl0eS1udW1iZXJPZlJldmlld3MtdG90YWwnXG4gICAgOiAncmV2aWV3cy1zdW1tYXJ5JztcbiAgY29uc3QgdHJ1c3RzY29yZUNvbnRhaW5lciA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRydXN0c2NvcmVDb250YWluZXJJZCk7XG4gIGNvbnN0IHNjb3JlRWxlbSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cnVzdC1zY29yZScpO1xuICBjb25zdCB0cmFuc2xhdGlvblN0cmluZyA9IG51bWJlck9mUmV2aWV3c1xuICAgID8gaW5zZXJ0TnVtYmVyU2VwYXJhdG9yKG51bWJlck9mUmV2aWV3cywgbG9jYWxlKVxuICAgIDogdHJhbnNsYXRpb25zLm5vUmV2aWV3cztcbiAgc2V0SHRtbENvbnRlbnQodHJ1c3RzY29yZUNvbnRhaW5lciwgdHJhbnNsYXRpb25TdHJpbmcpO1xuICBzZXRUZXh0Q29udGVudChzY29yZUVsZW0sIHRydXN0U2NvcmUudG9GaXhlZCgxKSk7XG4gIHNldFRleHRDb250ZW50KHJldmlld3NUZXh0Q29udGFpbmVyLCB0cmFuc2xhdGlvbnMucmV2aWV3cyk7XG59O1xuXG5jb25zdCBzaG93V3JhcHBlciA9ICgpID0+IHtcbiAgY29uc3Qgd3JhcHBlciA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cC13aWRnZXQtd3JhcHBlcicpO1xuICByZW1vdmVDbGFzcyh3cmFwcGVyLCAndHAtd2lkZ2V0LXdyYXBwZXItLXBsYWNlaG9sZGVyJyk7XG59O1xuXG5jb25zdCBhcHBseUN1c3RvbVN0eWxpbmcgPSAoKSA9PiB7XG4gIGlmIChmb250RmFtaWx5KSB7XG4gICAgc2V0Rm9udChmb250RmFtaWx5KTtcbiAgfVxuICBpZiAodGV4dENvbG9yKSB7XG4gICAgc2V0VGV4dENvbG9yKHRleHRDb2xvcik7XG4gIH1cbn07XG5cbmNvbnN0IHRyYW5zbGF0ZVRpdGxlID0gKHRyYW5zbGF0aW9ucykgPT4ge1xuICBpZiAodHJhbnNsYXRpb25zLnRydXN0cGlsb3RDdXN0b21XaWRnZXQpIHtcbiAgICBjb25zdCB0aXRsZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cC13aWRnZXQtdGl0bGUnKTtcbiAgICB0aXRsZS5pbm5lckhUTUwgPSB0cmFuc2xhdGlvbnMudHJ1c3RwaWxvdEN1c3RvbVdpZGdldDtcbiAgfVxufVxuXG5jb25zdCBjb25zdHJ1Y3RUcnVzdEJveCA9ICh7IGJhc2VEYXRhLCBsb2NhbGUgfSkgPT4ge1xuICBzaG93V3JhcHBlcigpO1xuICBzZXRIdG1sTGFuZ3VhZ2UobG9jYWxlKTtcbiAgaWYgKGJhc2VEYXRhLnRyYW5zbGF0aW9ucykgdHJhbnNsYXRlVGl0bGUoYmFzZURhdGEudHJhbnNsYXRpb25zKTtcbiAgcG9wdWxhdGVOdW1iZXJPZlJldmlld3MoeyBiYXNlRGF0YSwgbG9jYWxlIH0pO1xuICBwb3B1bGF0ZVN0YXJzKGJhc2VEYXRhLCAndHAtd2lkZ2V0LXN0YXJzJywgbnVsbCwgbG9jYWxlKTtcbiAgcG9wdWxhdGVMb2dvKCk7XG4gIGluamVjdFdpZGdldExpbmtzKHsgYmFzZURhdGEgfSk7XG4gIGlmIChiYXNlRGF0YS5zZXR0aW5ncy5jdXN0b21TdHlsZXNBbGxvd2VkKSB7XG4gICAgYXBwbHlDdXN0b21TdHlsaW5nKCk7XG4gIH1cbn07XG5cbmNvbnN0IGZldGNoUGFyYW1zID0ge1xuICBidXNpbmVzc1VuaXRJZCxcbiAgbG9jYWxlLFxuICB0aGVtZSxcbiAgbG9jYXRpb24sXG59O1xuXG5pbml0KCgpID0+IGZldGNoU2VydmljZVJldmlld0RhdGEodGVtcGxhdGVJZCkoZmV0Y2hQYXJhbXMsIGNvbnN0cnVjdFRydXN0Qm94KSk7XG4iLCJpbXBvcnQgeyBzZXRIdG1sQ29udGVudCwgbWFrZVRyYW5zbGF0aW9ucywgcmVtb3ZlRWxlbWVudCB9IGZyb20gJy4vdXRpbHMnO1xuXG5jb25zdCBoYXNDbGFzcyA9IChlbGVtLCBjbGFzc05hbWUpID0+IHtcbiAgaWYgKGVsZW0pIHtcbiAgICBjb25zdCBlbGVtQ2xhc3NMaXN0ID0gZWxlbS5nZXRBdHRyaWJ1dGUoJ2NsYXNzJyk7XG4gICAgY29uc3QgY2xhc3NOYW1lcyA9IGVsZW1DbGFzc0xpc3QgPyBlbGVtQ2xhc3NMaXN0LnNwbGl0KCcgJykgOiAnJztcbiAgICByZXR1cm4gY2xhc3NOYW1lcy5pbmRleE9mKGNsYXNzTmFtZSkgIT09IC0xO1xuICB9XG4gIHJldHVybiBmYWxzZTtcbn07XG5cbmNvbnN0IGFkZENsYXNzID0gKGVsZW0sIGZvckFkZGl0aW9uKSA9PiB7XG4gIGlmIChlbGVtKSB7XG4gICAgY29uc3QgZWxlbUNsYXNzTGlzdCA9IGVsZW0uZ2V0QXR0cmlidXRlKCdjbGFzcycpO1xuICAgIGNvbnN0IGNsYXNzTmFtZXMgPSBlbGVtQ2xhc3NMaXN0ID8gZWxlbUNsYXNzTGlzdC5zcGxpdCgnICcpIDogW107XG5cbiAgICBpZiAoIWhhc0NsYXNzKGVsZW0sIGZvckFkZGl0aW9uKSkge1xuICAgICAgY29uc3QgbmV3Q2xhc3NlcyA9IFsuLi5jbGFzc05hbWVzLCBmb3JBZGRpdGlvbl0uam9pbignICcpO1xuICAgICAgZWxlbS5zZXRBdHRyaWJ1dGUoJ2NsYXNzJywgbmV3Q2xhc3Nlcyk7XG4gICAgfVxuICB9XG59O1xuXG5jb25zdCByZW1vdmVDbGFzcyA9IChlbGVtLCBmb3JSZW1vdmFsKSA9PiB7XG4gIGlmIChlbGVtKSB7XG4gICAgY29uc3QgY2xhc3NOYW1lcyA9IGVsZW0uY2xhc3NOYW1lLnNwbGl0KCcgJyk7XG4gICAgZWxlbS5jbGFzc05hbWUgPSBjbGFzc05hbWVzLmZpbHRlcigobmFtZSkgPT4gbmFtZSAhPT0gZm9yUmVtb3ZhbCkuam9pbignICcpO1xuICB9XG59O1xuXG4vKipcbiAqIFBvcHVsYXRlcyBhIHNlcmllcyBvZiBlbGVtZW50cyB3aXRoIEhUTUwgY29udGVudC5cbiAqXG4gKiBGb3IgZWFjaCBvYmplY3QgaW4gYSBsaXN0LCBlaXRoZXIgYSBnaXZlbiBzdHJpbmcgdmFsdWUgaXMgdXNlZCB0byBwb3B1bGF0ZVxuICogdGhlIGdpdmVuIGVsZW1lbnQgKGluY2x1ZGluZyBvcHRpb25hbCBzdWJzdGl0dXRpb25zKTsgb3IsIHdoZXJlIG5vIHN0cmluZ1xuICogdmFsdWUgaXMgcHJvdmlkZWQsIHJlbW92ZSB0aGUgZ2l2ZW4gZWxlbWVudC5cbiAqL1xuY29uc3QgcG9wdWxhdGVFbGVtZW50cyA9IChlbGVtZW50cykgPT4ge1xuICBlbGVtZW50cy5mb3JFYWNoKCh7IGVsZW1lbnQsIHN0cmluZywgc3Vic3RpdHV0aW9ucyA9IHt9IH0pID0+IHtcbiAgICBpZiAoc3RyaW5nKSB7XG4gICAgICBzZXRIdG1sQ29udGVudChlbGVtZW50LCBtYWtlVHJhbnNsYXRpb25zKHN1YnN0aXR1dGlvbnMsIHN0cmluZyksIGZhbHNlKTtcbiAgICB9IGVsc2Uge1xuICAgICAgcmVtb3ZlRWxlbWVudChlbGVtZW50KTtcbiAgICB9XG4gIH0pO1xufTtcblxuZXhwb3J0IHsgYWRkQ2xhc3MsIHJlbW92ZUNsYXNzLCBoYXNDbGFzcywgcG9wdWxhdGVFbGVtZW50cyB9O1xuIiwiaW1wb3J0IHsgZ2V0QXNPYmplY3QgYXMgcXVlcnlTdHJpbmcgfSBmcm9tICcuL3F1ZXJ5U3RyaW5nJztcbmltcG9ydCB7IGFkZEV2ZW50TGlzdGVuZXIgfSBmcm9tICcuL3V0aWxzJztcbmltcG9ydCBnZXRXaWRnZXRSb290VXJpIGZyb20gJy4vcm9vdFVyaSc7XG5pbXBvcnQgeGhyIGZyb20gJy4veGhyJztcblxuZnVuY3Rpb24gc2V0Q29va2llKGNuYW1lLCBjdmFsdWUsIGV4cGlyZXMpIHtcbiAgY29uc3QgcGF0aCA9ICdwYXRoPS8nO1xuICBjb25zdCBkb21haW4gPSBgZG9tYWluPSR7d2luZG93LmxvY2F0aW9uLmhvc3RuYW1lLnJlcGxhY2UoL14uKlxcLihbXi5dK1xcLlteLl0rKS8sICckMScpfWA7XG4gIGNvbnN0IHNhbWVzaXRlID0gYHNhbWVzaXRlPW5vbmVgO1xuICBjb25zdCBzZWN1cmUgPSBgc2VjdXJlYDtcbiAgZG9jdW1lbnQuY29va2llID0gW2Ake2NuYW1lfT0ke2N2YWx1ZX1gLCBwYXRoLCBleHBpcmVzLCBkb21haW4sIHNhbWVzaXRlLCBzZWN1cmVdLmpvaW4oJzsgJyk7XG4gIGRvY3VtZW50LmNvb2tpZSA9IFtgJHtjbmFtZX0tbGVnYWN5PSR7Y3ZhbHVlfWAsIHBhdGgsIGV4cGlyZXMsIGRvbWFpbl0uam9pbignOyAnKTtcbn1cblxuZnVuY3Rpb24gbWFrZVRyYWNraW5nVXJsKGV2ZW50TmFtZSwgaW1wcmVzc2lvbkRhdGEpIHtcbiAgLy8gRGVzdHJ1Y3R1cmUgdGhlIGltcHJlc3Npb25EYXRhIGFuZCBxdWVyeSBwYXJhbXMgc28gdGhhdCB3ZSBvbmx5IHBhc3MgdGhlXG4gIC8vIGRlc2lyZWQgdmFsdWVzIGZvciBjb25zdHJ1Y3RpbmcgdGhlIHRyYWNraW5nIFVSTC5cbiAgY29uc3QgeyBhbm9ueW1vdXNJZDogdXNlcklkLCBzZXNzaW9uRXhwaXJ5OiBfLCAuLi5pbXByZXNzaW9uUGFyYW1zIH0gPSBpbXByZXNzaW9uRGF0YTtcbiAgY29uc3QgeyBidXNpbmVzc3VuaXRJZDogYnVzaW5lc3NVbml0SWQsIHRlbXBsYXRlSWQ6IHdpZGdldElkLCAuLi53aWRnZXRTZXR0aW5ncyB9ID0gcXVlcnlTdHJpbmcoKTtcblxuICBjb25zdCB1cmxQYXJhbXMgPSB7XG4gICAgLi4ud2lkZ2V0U2V0dGluZ3MsXG4gICAgLi4uaW1wcmVzc2lvblBhcmFtcyxcbiAgICAuLi4od2lkZ2V0U2V0dGluZ3MuZ3JvdXAgJiYgdXNlcklkID8geyB1c2VySWQgfSA6IHsgbm9zZXR0aW5nczogMSB9KSxcbiAgICBidXNpbmVzc1VuaXRJZCxcbiAgICB3aWRnZXRJZCxcbiAgfTtcbiAgY29uc3QgdXJsUGFyYW1zU3RyaW5nID0gT2JqZWN0LmtleXModXJsUGFyYW1zKVxuICAgIC5tYXAoKHByb3BlcnR5KSA9PiBgJHtwcm9wZXJ0eX09JHtlbmNvZGVVUklDb21wb25lbnQodXJsUGFyYW1zW3Byb3BlcnR5XSl9YClcbiAgICAuam9pbignJicpO1xuICByZXR1cm4gYCR7Z2V0V2lkZ2V0Um9vdFVyaSgpfS9zdGF0cy8ke2V2ZW50TmFtZX0/JHt1cmxQYXJhbXNTdHJpbmd9YDtcbn1cblxuZnVuY3Rpb24gc2V0VHJhY2tpbmdDb29raWVzKGV2ZW50TmFtZSwgeyBzZXNzaW9uLCB0ZXN0SWQsIHNlc3Npb25FeHBpcnkgfSkge1xuICBjb25zdCB7IGdyb3VwLCBidXNpbmVzc3VuaXRJZDogYnVzaW5lc3NVbml0SWQgfSA9IHF1ZXJ5U3RyaW5nKCk7XG4gIGlmICghZ3JvdXApIHtcbiAgICByZXR1cm47XG4gIH1cblxuICBpZiAoIXRlc3RJZCB8fCAhc2Vzc2lvbikge1xuICAgIC8vIGVzbGludC1kaXNhYmxlLW5leHQtbGluZVxuICAgIGNvbnNvbGUud2FybignVHJ1c3RCb3ggT3B0aW1pemVyIHRlc3QgZ3JvdXAgZGV0ZWN0ZWQgYnV0IG5vIHJ1bm5pbmcgdGVzdCBzZXR0aW5ncyBmb3VuZCEnKTtcbiAgfVxuXG4gIGlmIChzZXNzaW9uRXhwaXJ5KSB7XG4gICAgY29uc3Qgc2V0dGluZ3MgPSB7IGdyb3VwLCBzZXNzaW9uLCB0ZXN0SWQgfTtcbiAgICBzZXRDb29raWUoXG4gICAgICBgVHJ1c3Rib3hTcGxpdFRlc3RfJHtidXNpbmVzc1VuaXRJZH1gLFxuICAgICAgZW5jb2RlVVJJQ29tcG9uZW50KEpTT04uc3RyaW5naWZ5KHNldHRpbmdzKSksXG4gICAgICBzZXNzaW9uRXhwaXJ5XG4gICAgKTtcbiAgfVxufVxuXG5mdW5jdGlvbiB0cmFja0V2ZW50UmVxdWVzdChldmVudE5hbWUsIGltcHJlc3Npb25EYXRhKSB7XG4gIHNldFRyYWNraW5nQ29va2llcyhldmVudE5hbWUsIGltcHJlc3Npb25EYXRhKTtcbiAgY29uc3QgdXJsID0gbWFrZVRyYWNraW5nVXJsKGV2ZW50TmFtZSwgaW1wcmVzc2lvbkRhdGEpO1xuICB0cnkge1xuICAgIHhocih7IHVybCB9KTtcbiAgfSBjYXRjaCAoZSkge1xuICAgIC8vIGRvIG5vdGhpbmdcbiAgfVxufVxuXG5jb25zdCB0cmFja0ltcHJlc3Npb24gPSBmdW5jdGlvbiAoZGF0YSkge1xuICB0cmFja0V2ZW50UmVxdWVzdCgnVHJ1c3Rib3hJbXByZXNzaW9uJywgZGF0YSk7XG59O1xuXG5jb25zdCB0cmFja1ZpZXcgPSBmdW5jdGlvbiAoZGF0YSkge1xuICB0cmFja0V2ZW50UmVxdWVzdCgnVHJ1c3Rib3hWaWV3JywgZGF0YSk7XG59O1xuXG5jb25zdCB0cmFja0VuZ2FnZW1lbnQgPSBmdW5jdGlvbiAoZGF0YSkge1xuICB0cmFja0V2ZW50UmVxdWVzdCgnVHJ1c3Rib3hFbmdhZ2VtZW50JywgZGF0YSk7XG59O1xuXG5sZXQgaWQgPSBudWxsO1xuXG5jb25zdCBhdHRhY2hJbXByZXNzaW9uSGFuZGxlciA9IGZ1bmN0aW9uICgpIHtcbiAgYWRkRXZlbnRMaXN0ZW5lcih3aW5kb3csICdtZXNzYWdlJywgZnVuY3Rpb24gKGV2ZW50KSB7XG4gICAgaWYgKHR5cGVvZiBldmVudC5kYXRhICE9PSAnc3RyaW5nJykge1xuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIGxldCBlO1xuICAgIHRyeSB7XG4gICAgICBlID0geyBkYXRhOiBKU09OLnBhcnNlKGV2ZW50LmRhdGEpIH07XG4gICAgfSBjYXRjaCAoZSkge1xuICAgICAgLy8gcHJvYmFibHkgbm90IGZvciB1c1xuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIGlmIChlLmRhdGEuY29tbWFuZCA9PT0gJ3NldElkJykge1xuICAgICAgaWQgPSBlLmRhdGEud2lkZ2V0SWQ7XG4gICAgICB3aW5kb3cucGFyZW50LnBvc3RNZXNzYWdlKEpTT04uc3RyaW5naWZ5KHsgY29tbWFuZDogJ2ltcHJlc3Npb24nLCB3aWRnZXRJZDogaWQgfSksICcqJyk7XG4gICAgICByZXR1cm47XG4gICAgfVxuXG4gICAgaWYgKGUuZGF0YS5jb21tYW5kID09PSAnaW1wcmVzc2lvbi1yZWNlaXZlZCcpIHtcbiAgICAgIGRlbGV0ZSBlLmRhdGEuY29tbWFuZDtcbiAgICAgIHRyYWNrSW1wcmVzc2lvbihlLmRhdGEpO1xuICAgIH1cblxuICAgIGlmIChlLmRhdGEuY29tbWFuZCA9PT0gJ3RydXN0Ym94LWluLXZpZXdwb3J0Jykge1xuICAgICAgZGVsZXRlIGUuZGF0YS5jb21tYW5kO1xuICAgICAgdHJhY2tWaWV3KGUuZGF0YSk7XG4gICAgfVxuICB9KTtcbn07XG5cbmNvbnN0IHRyYWNraW5nID0ge1xuICBlbmdhZ2VtZW50OiB0cmFja0VuZ2FnZW1lbnQsXG4gIGF0dGFjaEltcHJlc3Npb25IYW5kbGVyLFxufTtcblxuZXhwb3J0IGRlZmF1bHQgdHJhY2tpbmc7XG4iLCJpbXBvcnQgeyBjb21wb3NlLCBwYWlyc1RvT2JqZWN0IH0gZnJvbSAnLi9mbic7XG5cbi8qKlxuICogQ29udmVydCBhIHBhcmFtZXRlciBzdHJpbmcgdG8gYW4gb2JqZWN0LlxuICovXG5mdW5jdGlvbiBwYXJhbXNUb09iamVjdChwYXJhbVN0cmluZykge1xuICBjb25zdCB0b2tlbnMgPSBbJz8nLCAnIyddO1xuICBjb25zdCBkcm9wRmlyc3RJZlRva2VuID0gKHN0cikgPT4gKHRva2Vucy5pbmRleE9mKHN0clswXSkgIT09IC0xID8gc3RyLnN1YnN0cmluZygxKSA6IHN0cik7XG4gIGNvbnN0IHRvUGFpcnMgPSAoc3RyKSA9PlxuICAgIHN0clxuICAgICAgLnNwbGl0KCcmJylcbiAgICAgIC5maWx0ZXIoQm9vbGVhbilcbiAgICAgIC5tYXAoKHBhaXJTdHJpbmcpID0+IHtcbiAgICAgICAgY29uc3QgW2tleSwgdmFsdWVdID0gcGFpclN0cmluZy5zcGxpdCgnPScpO1xuICAgICAgICB0cnkge1xuICAgICAgICAgIGNvbnN0IGRLZXkgPSBkZWNvZGVVUklDb21wb25lbnQoa2V5KTtcbiAgICAgICAgICBjb25zdCBkVmFsdWUgPSBkZWNvZGVVUklDb21wb25lbnQodmFsdWUpO1xuICAgICAgICAgIHJldHVybiBbZEtleSwgZFZhbHVlXTtcbiAgICAgICAgfSBjYXRjaCAoZSkge31cbiAgICAgIH0pXG4gICAgICAuZmlsdGVyKEJvb2xlYW4pO1xuICBjb25zdCBta09iamVjdCA9IGNvbXBvc2UocGFpcnNUb09iamVjdCwgdG9QYWlycywgZHJvcEZpcnN0SWZUb2tlbik7XG4gIHJldHVybiBta09iamVjdChwYXJhbVN0cmluZyk7XG59XG5cbi8qKlxuICogR2V0IGFsbCBwYXJhbXMgZnJvbSB0aGUgVHJ1c3RCb3gncyBVUkwuXG4gKlxuICogVGhlIG9ubHkgcXVlcnkgcGFyYW1ldGVycyByZXF1aXJlZCB0byBydW4gdGhlIGluaXRpYWwgbG9hZCBvZiBhIFRydXN0Qm94IGFyZVxuICogYnVzaW5lc3NVbml0SWQgYW5kIHRlbXBsYXRlSWQuIFRoZSByZXN0IGFyZSBvbmx5IHVzZWQgd2l0aGluIHRoZSBUcnVzdEJveCB0b1xuICogbWFrZSB0aGUgZGF0YSBjYWxsKHMpIGFuZCBzZXQgb3B0aW9ucy4gVGhlc2UgYXJlIHBhc3NlZCBhcyBwYXJ0IG9mIHRoZSBoYXNoXG4gKiB0byBlbnN1cmUgdGhhdCB3ZSBjYW4gcHJvcGVybHkgdXRpbGlzZSBicm93c2VyIGNhY2hpbmcuXG4gKlxuICogTm90ZTogdGhpcyBvbmx5IGNhcHR1cmVzIHNpbmdsZSBvY2N1cmVuY2VzIG9mIHZhbHVlcyBpbiB0aGUgVVJMLlxuICpcbiAqIEBwYXJhbSB7TG9jYXRpb259IGxvY2F0aW9uIC0gQSBsb2NhdGlvbiBvYmplY3QgZm9yIHdoaWNoIHRvIGdldCBxdWVyeSBwYXJhbXMuXG4gKiBAcmV0dXJuIHtPYmplY3R9IC0gQWxsIHF1ZXJ5IHBhcmFtcyBmb3IgdGhlIGdpdmVuIGxvY2F0aW9uLlxuICovXG5mdW5jdGlvbiBnZXRRdWVyeVBhcmFtcyhsb2NhdGlvbiA9IHdpbmRvdy5sb2NhdGlvbikge1xuICBjb25zdCBxdWVyeVBhcmFtcyA9IHBhcmFtc1RvT2JqZWN0KGxvY2F0aW9uLnNlYXJjaCk7XG4gIGNvbnN0IGhhc2hQYXJhbXMgPSBwYXJhbXNUb09iamVjdChsb2NhdGlvbi5oYXNoKTtcbiAgcmV0dXJuIHsgLi4ucXVlcnlQYXJhbXMsIC4uLmhhc2hQYXJhbXMgfTtcbn1cblxuZXhwb3J0IHtcbiAgZ2V0UXVlcnlQYXJhbXMsXG4gIGdldFF1ZXJ5UGFyYW1zIGFzIGdldEFzT2JqZWN0LCAvLyBGb3IgYmFja3dhcmRzIGNvbXBhdGliaWxpdHkgd2l0aCBUQnNcbn07XG4iLCIvKiBlc2xpbnQtZGlzYWJsZSBuby1jb25zb2xlICovXG5pbXBvcnQgeyBhZGRDbGFzcyB9IGZyb20gJy4vZG9tJztcbmltcG9ydCB7IHN0eWxlQWxpZ25tZW50UG9zaXRpb25zIH0gZnJvbSAnLi9tb2RlbHMvc3R5bGVBbGlnbm1lbnRQb3NpdGlvbnMnO1xuaW1wb3J0IGdldFdpZGdldFJvb3RVcmkgZnJvbSAnLi9yb290VXJpJztcblxuZnVuY3Rpb24gYWRkRXZlbnRMaXN0ZW5lcihlbGVtZW50LCB0eXBlLCBsaXN0ZW5lcikge1xuICBpZiAoZWxlbWVudCkge1xuICAgIGlmIChlbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIpIHtcbiAgICAgIGVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcih0eXBlLCBsaXN0ZW5lcik7XG4gICAgfSBlbHNlIHtcbiAgICAgIGVsZW1lbnQuYXR0YWNoRXZlbnQoYG9uJHt0eXBlfWAsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIGUgPSBlIHx8IHdpbmRvdy5ldmVudDtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCA9XG4gICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCB8fFxuICAgICAgICAgIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGUucmV0dXJuVmFsdWUgPSBmYWxzZTtcbiAgICAgICAgICB9O1xuICAgICAgICBlLnN0b3BQcm9wYWdhdGlvbiA9XG4gICAgICAgICAgZS5zdG9wUHJvcGFnYXRpb24gfHxcbiAgICAgICAgICBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBlLmNhbmNlbEJ1YmJsZSA9IHRydWU7XG4gICAgICAgICAgfTtcbiAgICAgICAgbGlzdGVuZXIuY2FsbChlbGVtZW50LCBlKTtcbiAgICAgIH0pO1xuICAgIH1cbiAgfVxufVxuXG5mdW5jdGlvbiBnZXRPblBhZ2VSZWFkeSgpIHtcbiAgcmV0dXJuIG5ldyBQcm9taXNlKGZ1bmN0aW9uIChyZXNvbHZlKSB7XG4gICAgY29uc3QgcmVzb2x2ZVdpdGhUaW1lb3V0ID0gZnVuY3Rpb24gKCkge1xuICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJlc29sdmUoKTtcbiAgICAgIH0sIDApO1xuICAgIH07XG4gICAgaWYgKGRvY3VtZW50LnJlYWR5U3RhdGUgPT09ICdjb21wbGV0ZScpIHtcbiAgICAgIHJlc29sdmVXaXRoVGltZW91dCgpO1xuICAgIH0gZWxzZSB7XG4gICAgICBhZGRFdmVudExpc3RlbmVyKHdpbmRvdywgJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJlc29sdmVXaXRoVGltZW91dCgpO1xuICAgICAgfSk7XG4gICAgfVxuICB9KTtcbn1cblxuZnVuY3Rpb24gaW5zZXJ0TnVtYmVyU2VwYXJhdG9yKGlucHV0LCBsb2NhbGUpIHtcbiAgdHJ5IHtcbiAgICBpbnB1dC50b0xvY2FsZVN0cmluZygpO1xuICB9IGNhdGNoIChlKSB7XG4gICAgcmV0dXJuIGlucHV0O1xuICB9XG4gIHJldHVybiBpbnB1dC50b0xvY2FsZVN0cmluZyhsb2NhbGUgfHwgJ2VuLVVTJyk7XG59XG5cbmZ1bmN0aW9uIHNldFRleHRDb250ZW50KGVsZW1lbnQsIGNvbnRlbnQpIHtcbiAgaWYgKCFlbGVtZW50KSB7XG4gICAgY29uc29sZS5sb2coJ0F0dGVtcHRpbmcgdG8gc2V0IGNvbnRlbnQgb24gbWlzc2luZyBlbGVtZW50Jyk7XG4gIH0gZWxzZSBpZiAoJ2lubmVyVGV4dCcgaW4gZWxlbWVudCkge1xuICAgIC8vIElFOFxuICAgIGVsZW1lbnQuaW5uZXJUZXh0ID0gY29udGVudDtcbiAgfSBlbHNlIHtcbiAgICBlbGVtZW50LnRleHRDb250ZW50ID0gY29udGVudDtcbiAgfVxufVxuXG5jb25zdCBzYW5pdGl6ZUh0bWxQcm9wID0gKHN0cmluZykgPT4ge1xuICBpZiAodHlwZW9mIHN0cmluZyA9PT0gJ3N0cmluZycpIHtcbiAgICBzdHJpbmcgPSBzdHJpbmcucmVwbGFjZUFsbCgnPicsICcnKTtcbiAgICBzdHJpbmcgPSBzdHJpbmcucmVwbGFjZUFsbCgnPCcsICcnKTtcbiAgICBzdHJpbmcgPSBzdHJpbmcucmVwbGFjZUFsbCgnXCInLCAnJyk7XG4gIH1cbiAgcmV0dXJuIHN0cmluZztcbn07XG5cbmNvbnN0IHNhbml0aXplSHRtbCA9IChzdHJpbmcpID0+IHtcbiAgaWYgKHR5cGVvZiBzdHJpbmcgIT09ICdzdHJpbmcnKSB7XG4gICAgcmV0dXJuIHN0cmluZztcbiAgfVxuICAvLyBUT0RPOiBHZXQgcmlkIG9mIDxhPiB0YWdzIGluIHRyYW5zbGF0aW9uc1xuICAvLyBSZW1vdmUgaHRtbCB0YWdzLCBleGNlcHQgPHA+IDxiPiA8aT4gPGxpPiA8dWw+IDxhPiA8c3Ryb25nPlxuICAvLyBCcmVha2Rvd246XG4gIC8vICAoPFxcLz8oPzpwfGJ8aXxsaXx1bHxhfHN0cm9uZylcXC8/Pikg4oCUIDFzdCBjYXB0dXJpbmcgZ3JvdXAsIHNlbGVjdHMgYWxsb3dlZCB0YWdzIChvcGVuaW5nIGFuZCBjbG9zaW5nKVxuICAvLyAgKD86PFxcLz8uKj9cXC8/Pikg4oCUIG5vbi1jYXB0dXJpbmcgZ3JvdXAgKD86KSwgbWF0Y2hlcyBhbGwgaHRtbCB0YWdzXG4gIC8vICAkMSDigJQga2VlcCBtYXRjaGVzIGZyb20gMXN0IGNhcHR1cmluZyBncm91cCBhcyBpcywgbWF0Y2hlcyBmcm9tIG5vbi1jYXB0dXJpbmcgZ3JvdXAgd2lsbCBiZSBvbWl0dGVkXG4gIC8vICAvZ2kg4oCUIGdsb2JhbCAobWF0Y2hlcyBhbGwgb2NjdXJyZW5jZXMpIGFuZCBjYXNlLWluc2Vuc2l0aXZlXG4gIC8vIFRlc3Q6IGh0dHBzOi8vcmVnZXgxMDEuY29tL3IvY0RhOGpyLzFcbiAgcmV0dXJuIHN0cmluZy5yZXBsYWNlKC8oPFxcLz8oPzpwfGJ8aXxsaXx1bHxhfHN0cm9uZylcXC8/Pil8KD86PFxcLz8uKj9cXC8/PikvZ2ksICckMScpO1xufTtcblxuLyoqXG4gKiBTYWZlbHkgc2V0cyBpbm5lckhUTUwgdG8gRE9NIGVsZW1lbnQuIEFsd2F5cyB1c2UgaXQgaW5zdGVhZCBvZiBzZXR0aW5nIC5pbm5lckhUTUwgZGlyZWN0bHkgb24gZWxlbWVudC5cbiAqIFNhbml0aXplcyBIVE1MIGJ5IGRlZmF1bHQuIFVzZSBzYW5pdGl6ZSBmbGFnIHRvIGNvbnRyb2wgdGhpcyBiZWhhdmlvdXIuXG4gKlxuICogQHBhcmFtIGVsZW1lbnRcbiAqIEBwYXJhbSBjb250ZW50XG4gKiBAcGFyYW0gc2FuaXRpemVcbiAqL1xuZnVuY3Rpb24gc2V0SHRtbENvbnRlbnQoZWxlbWVudCwgY29udGVudCwgc2FuaXRpemUgPSB0cnVlKSB7XG4gIGlmICghZWxlbWVudCkge1xuICAgIGNvbnNvbGUud2FybignQXR0ZW1wdGluZyB0byBzZXQgSFRNTCBjb250ZW50IG9uIG1pc3NpbmcgZWxlbWVudCcpO1xuICB9IGVsc2Uge1xuICAgIGVsZW1lbnQuaW5uZXJIVE1MID0gc2FuaXRpemUgPyBzYW5pdGl6ZUh0bWwoY29udGVudCkgOiBjb250ZW50O1xuICB9XG59XG5cbi8qKlxuICogSGVscGVyIGZ1bmN0aW9uLCBjaGVjayBpZiB0aGVcbiAqIEBwYXJhbSBhbGlnbm1lbnRcbiAqIEByZXR1cm5zIGB0cnVlYCBpZiB0aGUgc3VwcGxpZWQgdmFsdWUgaXMgbGVmdCBvciByaWdodCwgZmFsc2Ugb3RoZXJ3aXNlXG4gKi9cblxuY29uc3QgaXNWYWxpZEFsaWdubWVudCA9IChhbGlnbm1lbnQpID0+IHtcbiAgcmV0dXJuIHN0eWxlQWxpZ25tZW50UG9zaXRpb25zLmluY2x1ZGVzKGFsaWdubWVudCk7XG59O1xuXG4vKipcbiAqIFNldCB3aWRnZXQgYWxpZ25tZW50LCBhbGxvd2VkIHZhbHVlcyBhcmUgYGxlZnRgIGFuZCBgcmlnaHRgXG4gKlxuICogQHBhcmFtIGVsZW1lbnRJZFxuICogQHBhcmFtIGFsaWdubWVudFxuICovXG5jb25zdCBzZXRXaWRnZXRBbGlnbm1lbnQgPSAoZWxlbWVudElkLCBhbGlnbm1lbnQpID0+IHtcbiAgaWYgKCFlbGVtZW50SWQpIHtcbiAgICBjb25zb2xlLndhcm4oJ1RydXN0cGlsb3Q6IGNhbm5vdCBmaW5kIHN0YXJzIHdyYXBwZXIgZWxlbWVudCwgcGxlYXNlIGNvbnRhY3Qgc3VwcG9ydCEnKTtcbiAgICByZXR1cm47XG4gIH1cblxuICBpZiAoIWFsaWdubWVudCkge1xuICAgIGNvbnNvbGUud2FybignVHJ1c3RwaWxvdDogY2Fubm90IGFwcGx5IHdpZGdldCBhbGlnbm1lbnQsIHBsZWFzZSBjb250YWN0IHN1cHBvcnQhJyk7XG4gICAgcmV0dXJuO1xuICB9XG5cbiAgY29uc3QgaXNBbGlnbm1lbnRWYWxpZCA9IGlzVmFsaWRBbGlnbm1lbnQoYWxpZ25tZW50KTtcbiAgY29uc29sZS5sb2coJ2lzQWxpZ25tZW50VmFsaWQ6ICcsIGlzQWxpZ25tZW50VmFsaWQpO1xuXG4gIGlmICghaXNBbGlnbm1lbnRWYWxpZCkge1xuICAgIGNvbnNvbGUud2FybihcbiAgICAgIGBUcnVzdHBpbG90OiAke2FsaWdubWVudH0gaXMgbm90IGEgdmFsaWQgd2lkZ2V0IGFsaWdubWVudCB2YWx1ZSwgcGxlYXNlIGNvbnRhY3Qgc3VwcG9ydCFgXG4gICAgKTtcbiAgICByZXR1cm47XG4gIH1cblxuICBjb25zdCB3YXBwZXJFbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoZWxlbWVudElkKTtcblxuICBpZiAoIXdhcHBlckVsZW1lbnQpIHtcbiAgICBjb25zb2xlLmVycm9yKFwiVHJ1c3RwaWxvdDogY291bGRuJ3QgZmluZCB0aGUgc3RhcnMgd3JhcHBlciBlbGVtZW50LCBwbGVhc2UgY29udGFjdCBzdXBwb3J0IVwiKTtcbiAgICByZXR1cm47XG4gIH1cblxuICAvLyBOb3RlOiBFbGVtZW50J3MgSWQgYW5kIENsYXNzIE5hbWUgYXJlIHRoZSBzYW1lXG4gIHdhcHBlckVsZW1lbnQuY2xhc3NMaXN0LmFkZChgJHtlbGVtZW50SWR9LS0ke2FsaWdubWVudH1gKTtcbn07XG5cbi8qKlxuICogU2V0IHBvcHVwIGFsaWdubWVudCwgYWxsb3dlZCB2YWx1ZXMgYXJlIGxlZnQgYW5kIHJpZ2h0XG4gKiBAcGFyYW0ge3N0cmluZ30gYWxpZ25tZW50XG4gKiBAcmV0dXJucyBzZXQgdGhlIHBvc2l0aW9uIG9mIHRoZSBwb3B1cCBvZiB0aGUgUHJvZHVjdCBNaW5pIGFuZCBQcm9kdWN0IE1pbmkgSW1wb3J0ZWQgd2lkZ2V0cyB0byBgbGVmdGAgb3IgYHJpZ2h0YCwgYXMgcHJvdmlkZWQgYnkgdGhlIGh0bWwgZGF0YS0gZmllbGRcbiAqL1xuXG5jb25zdCBzZXRQb3B1cEFsaWdubWVudCA9IChhbGlnbm1lbnQpID0+IHtcbiAgaWYgKCFhbGlnbm1lbnQpIHtcbiAgICBjb25zb2xlLndhcm4oJ1RydXN0cGlsb3Q6IGNhbm5vdCBhcHBseSB3aWRnZXQgYWxpZ25tZW50LCBwbGVhc2UgY29udGFjdCBzdXBwb3J0IScpO1xuICAgIHJldHVybjtcbiAgfVxuXG4gIGNvbnN0IGlzQWxpZ25tZW50VmFsaWQgPSBpc1ZhbGlkQWxpZ25tZW50KGFsaWdubWVudCk7XG5cbiAgaWYgKCFpc0FsaWdubWVudFZhbGlkKSB7XG4gICAgY29uc29sZS53YXJuKFxuICAgICAgYFRydXN0cGlsb3Q6ICR7YWxpZ25tZW50fSBpcyBub3QgYSB2YWxpZCB2YWx1ZSBmb3Igc3R5bGUgYWxpZ25tZW50LCBwbGVhc2UgY29udGFjdCBzdXBwb3J0IWBcbiAgICApO1xuICAgIHJldHVybjtcbiAgfVxuXG4gIC8vIE5vdGU6IEVsZW1lbnQncyBJZCBhbmQgY2xhc3MgbmFtZSBoYXZlIHRoZSBzYW1lIHZhbHVlXG4gIGNvbnN0IHdpZGdldFBvcHVwV3JhcHBlckVsZW1lbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHAtd2lkZ2V0LXdyYXBwZXInKTtcbiAgaWYgKCF3aWRnZXRQb3B1cFdyYXBwZXJFbGVtZW50KSB7XG4gICAgY29uc29sZS5lcnJvcignVHJ1c3RwaWxvdDogd2lkZ2V0IHBvcHVwIGlzIG5vdCBmb3VuZCwgcGxlYXNlIGNvbnRhY3Qgc3VwcG9ydCEnKTtcbiAgICByZXR1cm47XG4gIH1cblxuICBjb25zdCBwb3B1cFN0eWxlQWxpZ25tZW50ID0gYHRwLXdpZGdldC13cmFwcGVyLS0ke2FsaWdubWVudH1gO1xuICB3aWRnZXRQb3B1cFdyYXBwZXJFbGVtZW50LmNsYXNzTGlzdC5hZGQocG9wdXBTdHlsZUFsaWdubWVudCk7XG59O1xuXG5mdW5jdGlvbiBtYWtlVHJhbnNsYXRpb25zKHRyYW5zbGF0aW9ucywgc3RyaW5nKSB7XG4gIGlmICghc3RyaW5nKSB7XG4gICAgY29uc29sZS5sb2coJ01pc3NpbmcgdHJhbnNsYXRpb24gc3RyaW5nJyk7XG4gICAgcmV0dXJuICcnO1xuICB9XG4gIHJldHVybiBPYmplY3Qua2V5cyh0cmFuc2xhdGlvbnMpLnJlZHVjZShcbiAgICAocmVzdWx0LCBrZXkpID0+IHJlc3VsdC5zcGxpdChrZXkpLmpvaW4odHJhbnNsYXRpb25zW2tleV0pLFxuICAgIHN0cmluZ1xuICApO1xufVxuXG5mdW5jdGlvbiByZW1vdmVFbGVtZW50KGVsZW1lbnQpIHtcbiAgaWYgKCFlbGVtZW50IHx8ICFlbGVtZW50LnBhcmVudE5vZGUpIHtcbiAgICBjb25zb2xlLmxvZygnQXR0ZW1wdGluZyB0byByZW1vdmUgYSBub24tZXhpc3RpbmcgZWxlbWVudCcpO1xuICAgIHJldHVybjtcbiAgfVxuICByZXR1cm4gZWxlbWVudC5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKGVsZW1lbnQpO1xufVxuXG5jb25zdCBzaG93VHJ1c3RCb3ggPSAodGhlbWUsIGhhc1Jldmlld3MpID0+IHtcbiAgY29uc3QgYm9keSA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlUYWdOYW1lKCdib2R5JylbMF07XG4gIGNvbnN0IHdyYXBwZXIgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHAtd2lkZ2V0LXdyYXBwZXInKTtcblxuICBhZGRDbGFzcyhib2R5LCB0aGVtZSk7XG4gIGFkZENsYXNzKHdyYXBwZXIsICd2aXNpYmxlJyk7XG5cbiAgaWYgKCFoYXNSZXZpZXdzKSB7XG4gICAgYWRkQ2xhc3MoYm9keSwgJ2ZpcnN0LXJldmlld2VyJyk7XG4gIH1cbn07XG5cbi8vIHVybCBjYW4gYWxyZWFkeSBoYXZlIHF1ZXJ5IHBhcmFtcyBpbiBpdFxuY29uc3QgdmVyaWZ5UXVlcnlQYXJhbVNlcGFyYXRvciA9ICh1cmwpID0+IGAke3VybH0ke3VybC5pbmRleE9mKCc/JykgPT09IC0xID8gJz8nIDogJyYnfWA7XG5cbmNvbnN0IGFkZFV0bVBhcmFtcyA9ICh0cnVzdEJveE5hbWUpID0+ICh1cmwpID0+XG4gIGAke3ZlcmlmeVF1ZXJ5UGFyYW1TZXBhcmF0b3IodXJsKX11dG1fbWVkaXVtPXRydXN0Ym94JnV0bV9zb3VyY2U9JHt0cnVzdEJveE5hbWV9YDtcblxuY29uc3QgcmVndWxhdGVGb2xsb3dGb3JMb2NhdGlvbiA9IChsb2NhdGlvbikgPT4gKGVsZW1lbnQpID0+IHtcbiAgaWYgKGxvY2F0aW9uICYmIGVsZW1lbnQpIHtcbiAgICBlbGVtZW50LnJlbCA9ICdub2ZvbGxvdyc7XG4gIH1cbn07XG5cbmNvbnN0IGluamVjdFdpZGdldExpbmtzID0gKGJhc2VEYXRhLCB1dG1UcnVzdEJveElkLCBsaW5rc0NsYXNzID0gJ3Byb2ZpbGUtdXJsJykgPT4ge1xuICBjb25zdCB7XG4gICAgYnVzaW5lc3NFbnRpdHk6IHtcbiAgICAgIG51bWJlck9mUmV2aWV3czogeyB0b3RhbDogbnVtYmVyT2ZSZXZpZXdzIH0sXG4gICAgfSxcbiAgICBsaW5rcyxcbiAgfSA9IGJhc2VEYXRhO1xuICBjb25zdCBpdGVtcyA9IFtdLnNsaWNlLmNhbGwoZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShsaW5rc0NsYXNzKSk7XG4gIGNvbnN0IGJhc2VVcmwgPSBudW1iZXJPZlJldmlld3MgPyBsaW5rcy5wcm9maWxlVXJsIDogbGlua3MuZXZhbHVhdGVVcmw7XG4gIGZvciAobGV0IGkgPSAwOyBpIDwgaXRlbXMubGVuZ3RoOyBpKyspIHtcbiAgICBpdGVtc1tpXS5ocmVmID0gYWRkVXRtUGFyYW1zKHV0bVRydXN0Qm94SWQpKGJhc2VVcmwpO1xuICB9XG59O1xuXG4vLyBDcmVhdGUgYSByYW5nZSBvZiBudW1iZXJzLCB1cCB0byAoYnV0IGV4Y2x1ZGluZykgdGhlIGFyZ3VtZW50LlxuLy8gV3JpdHRlbiB0byBzdXBwb3J0IElFMTEuXG5jb25zdCByYW5nZSA9IChudW0pID0+IHtcbiAgY29uc3QgcmVzdWx0ID0gW107XG4gIHdoaWxlIChudW0gPiAwKSB7XG4gICAgcmVzdWx0LnB1c2gocmVzdWx0Lmxlbmd0aCk7XG4gICAgbnVtLS07XG4gIH1cbiAgcmV0dXJuIHJlc3VsdDtcbn07XG5cbi8vIFNoaWZ0cyB0aGUgZ2l2ZW4gY29sb3IgdG8gZWl0aGVyIGxpZ2h0ZXIgb3IgZGFya2VyIGJhc2VkIG9uIHRoZSBiYXNlIHZhbHVlIGdpdmVuLlxuLy8gUG9zaXRpdmUgdmFsdWVzIGdpdmUgeW91IGxpZ2h0ZXIgY29sb3IsIG5lZ2F0aXZlIGRhcmtlci5cbmNvbnN0IGNvbG9yU2hpZnQgPSAoY29sLCBhbXQpID0+IHtcbiAgY29uc3QgdmFsaWRhdGVCb3VuZHMgPSAodikgPT4gKHYgPiAyNTUgPyAyNTUgOiB2IDwgMCA/IDAgOiB2KTtcbiAgbGV0IHVzZVBvdW5kID0gZmFsc2U7XG5cbiAgaWYgKGNvbFswXSA9PT0gJyMnKSB7XG4gICAgY29sID0gY29sLnNsaWNlKDEpO1xuICAgIHVzZVBvdW5kID0gdHJ1ZTtcbiAgfVxuXG4gIGNvbnN0IG51bSA9IHBhcnNlSW50KGNvbCwgMTYpO1xuICBpZiAoIW51bSkge1xuICAgIHJldHVybiBjb2w7XG4gIH1cblxuICBsZXQgciA9IChudW0gPj4gMTYpICsgYW10O1xuICByID0gdmFsaWRhdGVCb3VuZHMocik7XG5cbiAgbGV0IGcgPSAoKG51bSA+PiA4KSAmIDB4MDBmZikgKyBhbXQ7XG4gIGcgPSB2YWxpZGF0ZUJvdW5kcyhnKTtcblxuICBsZXQgYiA9IChudW0gJiAweDAwMDBmZikgKyBhbXQ7XG4gIGIgPSB2YWxpZGF0ZUJvdW5kcyhiKTtcblxuICBbciwgZywgYl0gPSBbciwgZywgYl0ubWFwKChjb2xvcikgPT5cbiAgICBjb2xvciA8PSAxNSA/IGAwJHtjb2xvci50b1N0cmluZygxNil9YCA6IGNvbG9yLnRvU3RyaW5nKDE2KVxuICApO1xuICByZXR1cm4gKHVzZVBvdW5kID8gJyMnIDogJycpICsgciArIGcgKyBiO1xufTtcblxuY29uc3QgaGV4VG9SR0JBID0gKGhleCwgYWxwaGEgPSAxKSA9PiB7XG4gIGNvbnN0IG51bSA9IGhleFswXSA9PT0gJyMnID8gcGFyc2VJbnQoaGV4LnNsaWNlKDEpLCAxNikgOiBwYXJzZUludChoZXgsIDE2KTtcbiAgY29uc3QgcmVkID0gbnVtID4+IDE2O1xuICBjb25zdCBncmVlbiA9IChudW0gPj4gOCkgJiAweDAwZmY7XG4gIGNvbnN0IGJsdWUgPSBudW0gJiAweDAwMDBmZjtcbiAgcmV0dXJuIGByZ2JhKCR7cmVkfSwke2dyZWVufSwke2JsdWV9LCR7YWxwaGF9KWA7XG59O1xuXG5jb25zdCBzZXRUZXh0Q29sb3IgPSAodGV4dENvbG9yKSA9PiB7XG4gIGNvbnN0IHRleHRDb2xvclN0eWxlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3R5bGUnKTtcbiAgdGV4dENvbG9yU3R5bGUuYXBwZW5kQ2hpbGQoXG4gICAgZG9jdW1lbnQuY3JlYXRlVGV4dE5vZGUoYFxuICAgICAgKiB7XG4gICAgICAgIGNvbG9yOiBpbmhlcml0ICFpbXBvcnRhbnQ7XG4gICAgICB9XG4gICAgICBib2R5IHtcbiAgICAgICAgY29sb3I6ICR7dGV4dENvbG9yfSAhaW1wb3J0YW50O1xuICAgICAgfVxuICAgICAgLmJvbGQtdW5kZXJsaW5lIHtcbiAgICAgICAgYm9yZGVyLWJvdHRvbS1jb2xvcjogJHt0ZXh0Q29sb3J9ICFpbXBvcnRhbnQ7XG4gICAgICB9XG4gICAgICAuYm9sZC11bmRlcmxpbmU6aG92ZXIge1xuICAgICAgICBib3JkZXItY29sb3I6ICR7Y29sb3JTaGlmdCh0ZXh0Q29sb3IsIC0zMCl9ICFpbXBvcnRhbnQ7XG4gICAgICB9XG4gICAgICAuc2Vjb25kYXJ5LXRleHQge1xuICAgICAgICBjb2xvcjogJHtoZXhUb1JHQkEodGV4dENvbG9yLCAwLjYpfSAhaW1wb3J0YW50O1xuICAgICAgfVxuICAgICAgLnNlY29uZGFyeS10ZXh0LWFycm93IHtcbiAgICAgICAgYm9yZGVyLWNvbG9yOiAke2hleFRvUkdCQSh0ZXh0Q29sb3IsIDAuNil9IHRyYW5zcGFyZW50IHRyYW5zcGFyZW50IHRyYW5zcGFyZW50ICFpbXBvcnRhbnQ7XG4gICAgICB9XG4gICAgICAucmVhZC1tb3JlIHtcbiAgICAgICAgY29sb3I6ICR7dGV4dENvbG9yfSAhaW1wb3J0YW50O1xuICAgICAgfVxuICAgIGApXG4gICk7XG4gIGRvY3VtZW50LmhlYWQuYXBwZW5kQ2hpbGQodGV4dENvbG9yU3R5bGUpO1xufTtcblxuY29uc3Qgc2V0Qm9yZGVyQ29sb3IgPSAoYm9yZGVyQ29sb3IpID0+IHtcbiAgY29uc3QgYm9yZGVyQ29sb3JTdHlsZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3N0eWxlJyk7XG4gIGJvcmRlckNvbG9yU3R5bGUuYXBwZW5kQ2hpbGQoXG4gICAgZG9jdW1lbnQuY3JlYXRlVGV4dE5vZGUoYFxuICAgICAqIHtcbiAgICAgICAgYm9yZGVyLWNvbG9yOiAke2JvcmRlckNvbG9yfSAhaW1wb3J0YW50O1xuICAgICAgfVxuICAgIGApXG4gICk7XG4gIGRvY3VtZW50LmhlYWQuYXBwZW5kQ2hpbGQoYm9yZGVyQ29sb3JTdHlsZSk7XG59O1xuXG5jb25zdCBzZXRGb250ID0gKGZvbnRGYW1pbHkpID0+IHtcbiAgY29uc3Qgd2lkZ2V0Um9vdFVyaSA9IGdldFdpZGdldFJvb3RVcmkoKTtcbiAgY29uc3QgZm9udEZhbWlseU5vcm1hbGl6ZWRGb3JVcmwgPSBmb250RmFtaWx5LnJlcGxhY2UoL1xccy9nLCAnLScpLnRvTG93ZXJDYXNlKCk7XG4gIGNvbnN0IGZvbnRMaW5rID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnbGluaycpO1xuICBmb250TGluay5yZWwgPSAnc3R5bGVzaGVldCc7XG4gIC8vIHdlIGFyZSB1c2luZyB0aGUgZm9sbG93aW5nIHRocmVlIHdlaWdodHMgaW4gbW9zdCBvZiBvdXIgVHJ1c3RCb3hlc1xuICAvLyBpbiBmdXR1cmUgaXRlcmF0aW9ucywgd2UgY2FuIG9wdGltaXplIHRoZSBieXRlcyB0cmFuc2ZlcnJlZCBieSBoYXZpbmcgYSBsaXN0IG9mIHdlaWdodHMgcGVyIFRydXN0Qm94XG4gIGZvbnRMaW5rLmhyZWYgPSBgJHt3aWRnZXRSb290VXJpfS9mb250cy8ke2ZvbnRGYW1pbHlOb3JtYWxpemVkRm9yVXJsfS5jc3NgO1xuICBkb2N1bWVudC5oZWFkLmFwcGVuZENoaWxkKGZvbnRMaW5rKTtcblxuICBjb25zdCBjbGVhbkZvbnROYW1lID0gZm9udEZhbWlseS5yZXBsYWNlKC9cXCsvZywgJyAnKTtcbiAgY29uc3QgZm9udFN0eWxlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3R5bGUnKTtcbiAgZm9udFN0eWxlLmFwcGVuZENoaWxkKFxuICAgIGRvY3VtZW50LmNyZWF0ZVRleHROb2RlKGBcbiAgICAqIHtcbiAgICAgIGZvbnQtZmFtaWx5OiBpbmhlcml0ICFpbXBvcnRhbnQ7XG4gICAgfVxuICAgIGJvZHkge1xuICAgICAgZm9udC1mYW1pbHk6IFwiJHtjbGVhbkZvbnROYW1lfVwiLCBzYW5zLXNlcmlmICFpbXBvcnRhbnQ7XG4gICAgfVxuICAgIGApXG4gICk7XG4gIGRvY3VtZW50LmhlYWQuYXBwZW5kQ2hpbGQoZm9udFN0eWxlKTtcbn07XG5cbmNvbnN0IHNldEh0bWxMYW5ndWFnZSA9IChsYW5ndWFnZSkgPT4ge1xuICBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuc2V0QXR0cmlidXRlKCdsYW5nJywgbGFuZ3VhZ2UpO1xufTtcblxuY29uc3Qgc2FuaXRpemVDb2xvciA9IChjb2xvcikgPT4ge1xuICBjb25zdCBoZXhSZWdFeHAgPSAvXiMoPzpbXFxkYS1mQS1GXXszfSl7MSwyfSQvO1xuICByZXR1cm4gdHlwZW9mIGNvbG9yID09PSAnc3RyaW5nJyAmJiBoZXhSZWdFeHAudGVzdChjb2xvcikgPyBjb2xvciA6IG51bGw7XG59O1xuXG5jb25zdCBoYW5kbGVQb3BvdmVyUG9zaXRpb24gPSAobGFiZWwsIHBvcG92ZXIsIGNvbnRhaW5lciwgcG9wVXBBcnJvdykgPT4ge1xuICBjb25zdCBwb3BvdmVyUmVjdCA9IHBvcG92ZXIuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XG4gIGNvbnN0IGNvbnRhaW5lclJlY3QgPSBjb250YWluZXIuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XG4gIGNvbnN0IGxhYmVsUmVjdCA9IGxhYmVsLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xuXG4gIGlmIChwb3BvdmVyUmVjdC5sZWZ0IDwgY29udGFpbmVyUmVjdC5sZWZ0KSB7XG4gICAgLy8gV2UgbmVlZCB0byBzdGljayB0aGUgcG9wb3ZlciB0byB0aGUgbGVmdCBzaWRlIG9mIHRoZSBjb250YWluZXJcbiAgICAvLyBCZWNhdXNlIHRoZSBgbGVmdGAgYW5kIGByaWdodGAgdmFsdWVzIGFyZSByZWxhdGl2ZSB0byB0aGUgcGFyZW50LFxuICAgIC8vIHdlIG5lZWQgdG8gbWFrZSB0aGUgZm9sbG93aW5nIGNhbGN1bGF0aW9uIHRvIGZpbmQgd2hlcmUgdG8gc3RpY2sgdGhlIHBvcG92ZXJcbiAgICBwb3BvdmVyLnN0eWxlLmxlZnQgPSBgJHtjb250YWluZXJSZWN0LmxlZnQgLSBsYWJlbFJlY3QubGVmdH1weGA7XG4gICAgcG9wb3Zlci5zdHlsZS5yaWdodCA9ICdhdXRvJztcbiAgICAvLyBNb3ZpbmcgdGhlIGFycm93IGJ5IHRoZSBkaXN0YW5jZSBvZiB0aGUgcG9wb3ZlciBzaGlmdCBvdmVyIFggYXhpc1xuICAgIGNvbnN0IG5ld1BvcHVwUmVjdCA9IHBvcG92ZXIuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XG4gICAgY29uc3QgY3VycmVudExlZnRWYWx1ZSA9IGdldENvbXB1dGVkU3R5bGUocG9wVXBBcnJvdykubGVmdDtcbiAgICBwb3BVcEFycm93LnN0eWxlLmxlZnQgPSBgY2FsYygke2N1cnJlbnRMZWZ0VmFsdWV9ICsgJHtNYXRoLmZsb29yKFxuICAgICAgcG9wb3ZlclJlY3QubGVmdCAtIG5ld1BvcHVwUmVjdC5sZWZ0XG4gICAgKX1weClgO1xuICB9IGVsc2UgaWYgKHBvcG92ZXJSZWN0LnJpZ2h0ID4gY29udGFpbmVyUmVjdC5yaWdodCkge1xuICAgIC8vIFdlIG5lZWQgdG8gc3RpY2sgdGhlIHBvcG92ZXIgdG8gdGhlIHJpZ2h0IHNpZGUgb2YgdGhlIGNvbnRhaW5lclxuICAgIC8vIEJlY2F1c2UgdGhlIGBsZWZ0YCBhbmQgYHJpZ2h0YCB2YWx1ZXMgYXJlIHJlbGF0aXZlIHRvIHRoZSBwYXJlbnQsXG4gICAgLy8gd2UgbmVlZCB0byBtYWtlIHRoZSBmb2xsb3dpbmcgY2FsY3VsYXRpb24gdG8gZmluZCB3aGVyZSB0byBzdGljayB0aGUgcG9wb3ZlclxuICAgIHBvcG92ZXIuc3R5bGUucmlnaHQgPSBgJHtsYWJlbFJlY3QucmlnaHQgLSBjb250YWluZXJSZWN0LnJpZ2h0fXB4YDtcbiAgICBwb3BvdmVyLnN0eWxlLmxlZnQgPSAnYXV0byc7XG4gICAgLy8gTW92aW5nIHRoZSBhcnJvdyBieSB0aGUgZGlzdGFuY2Ugb2YgdGhlIHBvcG92ZXIgc2hpZnQgb3ZlciBYIGF4aXNcbiAgICBjb25zdCBuZXdQb3B1cFJlY3QgPSBwb3BvdmVyLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xuICAgIGNvbnN0IGN1cnJlbnRMZWZ0VmFsdWUgPSBnZXRDb21wdXRlZFN0eWxlKHBvcFVwQXJyb3cpLmxlZnQ7XG4gICAgcG9wVXBBcnJvdy5zdHlsZS5sZWZ0ID0gYGNhbGMoJHtjdXJyZW50TGVmdFZhbHVlfSArICR7TWF0aC5mbG9vcihcbiAgICAgIHBvcG92ZXJSZWN0LnJpZ2h0IC0gbmV3UG9wdXBSZWN0LnJpZ2h0XG4gICAgKX1weClgO1xuICB9XG59O1xuXG5jb25zdCBzb3J0QXR0cmlidXRlUmF0aW5ncyA9IChhdHRyaWJ1dGVSYXRpbmdzQXJyYXkpID0+IHtcbiAgY29uc3Qgc29ydEJ5TmFtZSA9IChhLCBiKSA9PiBhLm5hbWUubG9jYWxlQ29tcGFyZShiLm5hbWUpO1xuXG4gIGNvbnN0IHN0YXJBdHRyaWJ1dGVzID0gYXR0cmlidXRlUmF0aW5nc0FycmF5XG4gICAgLmZpbHRlcigoeCkgPT4geC50eXBlID09PSAncmFuZ2VfMXRvNScpXG4gICAgLnNvcnQoc29ydEJ5TmFtZSk7XG4gIGNvbnN0IHNjYWxlQXR0cmlidXRlcyA9IGF0dHJpYnV0ZVJhdGluZ3NBcnJheS5maWx0ZXIoKHgpID0+IHgudHlwZSA9PT0gJ3NjYWxlJykuc29ydChzb3J0QnlOYW1lKTtcblxuICByZXR1cm4gWy4uLnN0YXJBdHRyaWJ1dGVzLCAuLi5zY2FsZUF0dHJpYnV0ZXNdO1xufTtcblxuY29uc3QgZ2V0VHJ1c3RwaWxvdEJ1c2luZXNzVW5pdElkID0gKCkgPT4ge1xuICAvLyBUaGlzIHdpbGwgYmUgc3Vic3RpdHV0ZWQgd2l0aGluIHRoZSBidWlsZHNjcmlwdHNcbiAgY29uc3QgYnVpZCA9ICcje1RydXN0cGlsb3RCdXNpbmVzc1VuaXRJZH0nO1xuICByZXR1cm4gYnVpZC5pbmRleE9mKCcjJykgPT09IDAgPyAnNDZkNmE4OTAwMDAwNjQwMDA1MDBlMGMzJyA6IGJ1aWQ7XG59O1xuXG5leHBvcnQge1xuICBhZGRFdmVudExpc3RlbmVyLFxuICBhZGRVdG1QYXJhbXMsXG4gIGdldE9uUGFnZVJlYWR5LFxuICBnZXRUcnVzdHBpbG90QnVzaW5lc3NVbml0SWQsXG4gIGluamVjdFdpZGdldExpbmtzLFxuICBpbnNlcnROdW1iZXJTZXBhcmF0b3IsXG4gIGhhbmRsZVBvcG92ZXJQb3NpdGlvbixcbiAgbWFrZVRyYW5zbGF0aW9ucyxcbiAgcmFuZ2UsXG4gIHJlZ3VsYXRlRm9sbG93Rm9yTG9jYXRpb24sXG4gIHJlbW92ZUVsZW1lbnQsXG4gIHNhbml0aXplQ29sb3IsXG4gIHNhbml0aXplSHRtbCxcbiAgc2FuaXRpemVIdG1sUHJvcCxcbiAgc2V0Qm9yZGVyQ29sb3IsXG4gIHNldEh0bWxDb250ZW50LFxuICBzZXRIdG1sTGFuZ3VhZ2UsXG4gIHNldEZvbnQsXG4gIHNldFBvcHVwQWxpZ25tZW50LFxuICBzZXRUZXh0Q29sb3IsXG4gIHNldFRleHRDb250ZW50LFxuICBzZXRXaWRnZXRBbGlnbm1lbnQsXG4gIHNob3dUcnVzdEJveCxcbiAgc29ydEF0dHJpYnV0ZVJhdGluZ3MsXG59O1xuIiwiaW1wb3J0IHsgcGluZywgb25Qb25nIH0gZnJvbSAnLi4vY29tbXVuaWNhdGlvbic7XG5pbXBvcnQgeyBlcnJvckZhbGxiYWNrIH0gZnJvbSAnLi90ZW1wbGF0ZXMvZXJyb3JGYWxsYmFjayc7XG5cbmNvbnN0IEZBTExCQUNLX0RFTEFZID0gNTAwO1xuXG4vKipcbiAqIE1ha2VzIHN1cmUgdGhhdCB0aGUgd2lkZ2V0IGlzIGluaXRpYWxpemVkIG9ubHkgd2hlbiB0aGUgYm9vdHN0cmFwcGVyIGlzIHByZXNlbnQuXG4gKlxuICogU2VuZHMgYSBcInBpbmdcIiBtZXNzYWdlIHRvIHRoZSBib290c3RyYXBwZXIgYW5kIHdhaXRzIGZvciBhIFwicG9uZ1wiIHJlcGx5IGJlZm9yZSBpbml0aWFsaXppbmcgdGhlIHdpZGdldC5cbiAqXG4gKiBAcGFyYW0ge0Z1bmN0aW9ufSBvbkluaXQgdGhlIGNhbGxiYWNrIHRvIGJlIGV4ZWN1dGVkIHdoZW4gdGhlIGluaXQgaXMgZG9uZS5cbiAqL1xuY29uc3QgaW5pdCA9IChvbkluaXQpID0+IHtcbiAgbGV0IGluaXRpYWxpemVkID0gZmFsc2U7XG4gIG9uUG9uZygoKSA9PiB7XG4gICAgaW5pdGlhbGl6ZWQgPSB0cnVlO1xuICAgIGlmICh0eXBlb2Ygb25Jbml0ID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICBvbkluaXQoKTtcbiAgICB9IGVsc2Uge1xuICAgICAgY29uc29sZS53YXJuKCdgb25Jbml0YCBub3Qgc3VwcGxpZWQnKTtcbiAgICB9XG4gIH0pO1xuXG4gIHBpbmcoKTtcblxuICAvLyB3ZSB3YW50IHRvIGF2b2lkIHJlbmRlcmluZyB0aGUgZmFsbGJhY2sgcmlnaHQgYXdheSBpbiBjYXNlIHRoZSBcInBvbmdcIiBtZXNzYWdlIGZyb20gdGhlIGJvb3RzdHJhcHBlciBjb21lcyBiYWNrIGltbWVkaWF0ZWx5XG4gIC8vIHRoaXMgd2F5IHdlIHdpbGwgYXZvaWQgYSBmbGlja2VyIGZyb20gXCJlbXB0eSBzY3JlZW5cIiAtPiBcImZhbGxiYWNrXCIgLT4gXCJUcnVzdEJveFwiIGFuZCBoYXZlIFwiZW1wdHkgc2NyZWVuXCIgLT4gXCJUcnVzdEJveFwiXG4gIHNldFRpbWVvdXQoKCkgPT4ge1xuICAgIGlmICghaW5pdGlhbGl6ZWQpIHtcbiAgICAgIGVycm9yRmFsbGJhY2soKTtcbiAgICB9XG4gIH0sIEZBTExCQUNLX0RFTEFZKTtcbn07XG5cbmV4cG9ydCBkZWZhdWx0IGluaXQ7XG4iLCJpbXBvcnQgeyBkaXYsIG1rRWxlbVdpdGhTdmcgfSBmcm9tICcuLi90ZW1wbGF0aW5nJztcbmltcG9ydCB7IHBvcHVsYXRlRWxlbWVudHMgfSBmcm9tICcuLi8uLi9kb20nO1xuaW1wb3J0IHsgc2FuaXRpemVDb2xvciB9IGZyb20gJy4uLy4uL3V0aWxzJztcbmltcG9ydCB7IGRlZmF1bHRMb2NhbGUgfSBmcm9tICcuLi90cmFuc2xhdGlvbnMnO1xuaW1wb3J0IHsgc3RhcnMgfSBmcm9tICcuLi9hc3NldHMvc3RhcnMnO1xuXG5jb25zdCBtYWtlU3RhcnMgPSAoeyBudW0sIHRydXN0U2NvcmUgPSBudWxsLCB3cmFwcGVyQ2xhc3MgPSAnJywgY29sb3IsIGxvY2FsZSwgdHJhbnNsYXRpb25zIH0pID0+IHtcbiAgY29uc3QgZnVsbFBhcnQgPSBNYXRoLmZsb29yKG51bSk7XG4gIGNvbnN0IGhhbGZQYXJ0ID0gbnVtID09PSBmdWxsUGFydCA/ICcnIDogYCB0cC1zdGFycy0tJHtmdWxsUGFydH0tLWhhbGZgO1xuICBjb25zdCBzYW5pdGl6ZWRDb2xvciA9IHNhbml0aXplQ29sb3IoY29sb3IpO1xuICByZXR1cm4gZGl2KFxuICAgIHsgY2xhc3M6IHdyYXBwZXJDbGFzcyB9LFxuICAgIC8vIGFkZCBhIGRpZmZlcmVudCBjbGFzcyBzbyB0aGF0IHN0eWxlcyBmcm9tIHdpZGdldHMtc3R5bGVndWlkZSBkbyBub3QgYXBwbHlcbiAgICBta0VsZW1XaXRoU3ZnKFxuICAgICAgc3RhcnMsXG4gICAgICBgJHtzYW5pdGl6ZWRDb2xvciA/ICd0cC1zdGFycy1jdXN0b20tY29sb3InIDogYHRwLXN0YXJzIHRwLXN0YXJzLS0ke2Z1bGxQYXJ0fSR7aGFsZlBhcnR9YH1gLFxuICAgICAgeyByYXRpbmc6IG51bSwgdHJ1c3RTY29yZTogdHJ1c3RTY29yZSB8fCBudW0sIGNvbG9yOiBzYW5pdGl6ZWRDb2xvciwgbG9jYWxlLCB0cmFuc2xhdGlvbnMgfVxuICAgIClcbiAgKTtcbn07XG5cbmNvbnN0IHBvcHVsYXRlU3RhcnMgPSAoXG4gIHtcbiAgICBidXNpbmVzc0VudGl0eToge1xuICAgICAgc3RhcnMsXG4gICAgICB0cnVzdFNjb3JlLFxuICAgICAgbnVtYmVyT2ZSZXZpZXdzOiB7IHRvdGFsIH0sXG4gICAgfSxcbiAgICB0cmFuc2xhdGlvbnMsXG4gIH0sXG4gIHN0YXJzQ29udGFpbmVyID0gJ3RwLXdpZGdldC1zdGFycycsXG4gIHN0YXJzQ29sb3IsXG4gIGxvY2FsZSA9IGRlZmF1bHRMb2NhbGVcbikgPT4ge1xuICBjb25zdCBzYW5pdGl6ZWRDb2xvciA9IHNhbml0aXplQ29sb3Ioc3RhcnNDb2xvcik7XG4gIGNvbnN0IGNvbnRhaW5lciA9XG4gICAgdHlwZW9mIHN0YXJzQ29udGFpbmVyID09PSAnc3RyaW5nJyA/IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHN0YXJzQ29udGFpbmVyKSA6IHN0YXJzQ29udGFpbmVyO1xuICAvLyBFbnN1cmUgd2UgcHJvcGVybHkgaGFuZGxlIGVtcHR5IHJldmlldyBzdGF0ZSAtIHdlIHNvbWV0aW1lcyBnZXQgYSByYXRpbmdcbiAgLy8gYmFjayBmcm9tIHRoZSBBUEkgZXZlbiB3aGVyZSB3ZSBoYXZlIG5vIHJldmlld3MsIHNvIGV4cGxpY2l0bHkgY2hlY2suXG4gIGNvbnN0IGRpc3BsYXllZFN0YXJzID0gdG90YWwgPyBzdGFycyA6IDA7XG5cbiAgcG9wdWxhdGVFbGVtZW50cyhbXG4gICAge1xuICAgICAgZWxlbWVudDogY29udGFpbmVyLFxuICAgICAgc3RyaW5nOiBtYWtlU3RhcnMoe1xuICAgICAgICBudW06IGRpc3BsYXllZFN0YXJzLFxuICAgICAgICB0cnVzdFNjb3JlLFxuICAgICAgICBjb2xvcjogc2FuaXRpemVkQ29sb3IsXG4gICAgICAgIGxvY2FsZSxcbiAgICAgICAgdHJhbnNsYXRpb25zLFxuICAgICAgfSksXG4gICAgfSxcbiAgXSk7XG59O1xuXG5leHBvcnQgeyBtYWtlU3RhcnMsIHBvcHVsYXRlU3RhcnMgfTtcbiIsImltcG9ydCB7IG1rRWxlbVdpdGhTdmcgfSBmcm9tICcuLi90ZW1wbGF0aW5nJztcbmltcG9ydCB7IHBvcHVsYXRlRWxlbWVudHMgfSBmcm9tICcuLi8uLi9kb20nO1xuaW1wb3J0IHsgbG9nbyB9IGZyb20gJy4uL2Fzc2V0cy9sb2dvJztcblxuY29uc3QgbWFrZUxvZ28gPSAoKSA9PiBta0VsZW1XaXRoU3ZnKGxvZ28pO1xuXG5jb25zdCBwb3B1bGF0ZUxvZ28gPSAobG9nb0NvbnRhaW5lciA9ICd0cC13aWRnZXQtbG9nbycpID0+IHtcbiAgY29uc3QgY29udGFpbmVyID1cbiAgICB0eXBlb2YgbG9nb0NvbnRhaW5lciA9PT0gJ3N0cmluZycgPyBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChsb2dvQ29udGFpbmVyKSA6IGxvZ29Db250YWluZXI7XG5cbiAgcG9wdWxhdGVFbGVtZW50cyhbXG4gICAge1xuICAgICAgZWxlbWVudDogY29udGFpbmVyLFxuICAgICAgc3RyaW5nOiBtYWtlTG9nbygpLFxuICAgIH0sXG4gIF0pO1xufTtcblxuZXhwb3J0IHsgbWFrZUxvZ28sIHBvcHVsYXRlTG9nbyB9O1xuIiwiaW1wb3J0IHtcbiAgZmV0Y2hEYXRhLFxuICBtdWx0aUZldGNoRGF0YSxcbiAgY29uc3RydWN0VHJ1c3RCb3hBbmRDb21wbGV0ZSxcbiAgaGFzU2VydmljZVJldmlld3MsXG4gIGhhc1NlcnZpY2VSZXZpZXdzTXVsdGlGZXRjaCxcbn0gZnJvbSAnLi9mZXRjaERhdGEnO1xuaW1wb3J0IHtcbiAgZmV0Y2hQcm9kdWN0RGF0YSxcbiAgZmV0Y2hQcm9kdWN0UmV2aWV3XG59IGZyb20gJy4vcHJvZHVjdFJldmlld3MnO1xuXG5jb25zdCBmZXRjaFNlcnZpY2VSZXZpZXdEYXRhID0gKHRlbXBsYXRlSWQpID0+IChmZXRjaFBhcmFtcywgY29uc3RydWN0VHJ1c3RCb3gsIHBhc3NUb1BvcHVwKSA9PiB7XG4gIGZldGNoRGF0YShgL3RydXN0Ym94LWRhdGEvJHt0ZW1wbGF0ZUlkfWApKFxuICAgIGZldGNoUGFyYW1zLFxuICAgIGNvbnN0cnVjdFRydXN0Qm94LFxuICAgIHBhc3NUb1BvcHVwLFxuICAgIGhhc1NlcnZpY2VSZXZpZXdzXG4gICk7XG59O1xuXG5jb25zdCBmZXRjaFNlcnZpY2VSZXZpZU11bHRpcGxlRGF0YSA9ICh0ZW1wbGF0ZUlkKSA9PiAoXG4gIGZldGNoUGFyYW1zLFxuICBjb25zdHJ1Y3RUcnVzdEJveCxcbiAgcGFzc1RvUG9wdXBcbikgPT4ge1xuICBtdWx0aUZldGNoRGF0YShgL3RydXN0Ym94LWRhdGEvJHt0ZW1wbGF0ZUlkfWApKFxuICAgIGZldGNoUGFyYW1zLFxuICAgIGNvbnN0cnVjdFRydXN0Qm94LFxuICAgIHBhc3NUb1BvcHVwLFxuICAgIGhhc1NlcnZpY2VSZXZpZXdzTXVsdGlGZXRjaFxuICApO1xufTtcblxuZXhwb3J0IHtcbiAgZmV0Y2hQcm9kdWN0RGF0YSxcbiAgZmV0Y2hQcm9kdWN0UmV2aWV3LFxuICBjb25zdHJ1Y3RUcnVzdEJveEFuZENvbXBsZXRlLFxuICBmZXRjaFNlcnZpY2VSZXZpZXdEYXRhLFxuICBmZXRjaFNlcnZpY2VSZXZpZU11bHRpcGxlRGF0YSxcbn07XG4iLCJpbXBvcnQgeGhyIGZyb20gJy4uL3hocic7XG5pbXBvcnQgeyBnZXRBc09iamVjdCBhcyBnZXRRdWVyeXN0cmluZ0FzT2JqZWN0IH0gZnJvbSAnLi4vcXVlcnlTdHJpbmcnO1xuaW1wb3J0IGdldFdpZGdldFJvb3RVcmkgZnJvbSAnLi4vcm9vdFVyaSc7XG5cbi8vIE1ha2UgYSByYW5kb20gSUQgd2hlcmUgYW4gYXBpQ2FsbCByZXF1aXJlcyBvbmUuXG5jb25zdCBtYWtlSWQgPSAobnVtT2ZDaGFycykgPT4ge1xuICBsZXQgdGV4dCA9ICcnO1xuICBjb25zdCBwb3NzaWJsZSA9ICdBQkNERUZHSElKS0xNTk9QUVJTVFVWV1hZWmFiY2RlZmdoaWprbG1ub3BxcnN0dXZ3eHl6MDEyMzQ1Njc4OSc7XG4gIGZvciAobGV0IGkgPSAwOyBpIDwgbnVtT2ZDaGFyczsgaSsrKSB7XG4gICAgdGV4dCArPSBwb3NzaWJsZS5jaGFyQXQoTWF0aC5mbG9vcihNYXRoLnJhbmRvbSgpICogcG9zc2libGUubGVuZ3RoKSk7XG4gIH1cbiAgcmV0dXJuIHRleHQ7XG59O1xuXG4vKiBlc2xpbnQtZGlzYWJsZSBjb21wYXQvY29tcGF0ICovXG5jb25zdCBhcGlDYWxsID0gKHVyaSwgcGFyYW1zKSA9PlxuICBuZXcgUHJvbWlzZSgocmVzb2x2ZSwgZmFpbCkgPT4ge1xuICAgIGxldCB2YWx1ZXM7XG4gICAgbGV0IHVybDtcblxuICAgIGlmICh1cmkuaW5kZXhPZignLycpID09PSAwKSB7XG4gICAgICB2YWx1ZXMgPSBwYXJhbXMgfHwge307XG4gICAgICBjb25zdCB7IHRva2VuIH0gPSBnZXRRdWVyeXN0cmluZ0FzT2JqZWN0KCk7XG4gICAgICBpZiAodG9rZW4pIHtcbiAgICAgICAgdmFsdWVzLnJhbmRvbSA9IG1ha2VJZCgyMCk7XG4gICAgICB9XG4gICAgfVxuXG4gICAgaWYgKHVyaS5pbmRleE9mKCdodHRwJykgPT09IDApIHtcbiAgICAgIC8vIGlzIGEgZnVsbCB1cmwgZnJvbSBhIHBhZ2luZyBsaW5rLCBlbnN1cmUgaHR0cHNcbiAgICAgIHVybCA9IHVyaS5yZXBsYWNlKC9eaHR0cHM/Oi8sICdodHRwczonKTtcbiAgICB9IGVsc2UgaWYgKHVyaS5pbmRleE9mKCcvJykgPT09IDApIHtcbiAgICAgIC8vIGlzIGEgcmVndWxhciBcIi92MS8uLi5cIiBhZGQgZG9tYWluIGZvciBsb2NhbCB0ZXN0aW5nICh2YWx1ZSBpcyBlbXB0eSBpbiBwcm9kKVxuICAgICAgdXJsID0gZ2V0V2lkZ2V0Um9vdFVyaSgpICsgdXJpO1xuICAgIH0gZWxzZSB7XG4gICAgICAvLyB3ZWlyZC9icm9rZW4gdXJsXG4gICAgICByZXR1cm4gZmFpbCgpO1xuICAgIH1cblxuICAgIHJldHVybiB4aHIoe1xuICAgICAgdXJsLFxuICAgICAgZGF0YTogdmFsdWVzLFxuICAgICAgc3VjY2VzczogcmVzb2x2ZSxcbiAgICAgIGVycm9yOiBmYWlsLFxuICAgIH0pO1xuICB9KTtcblxuZXhwb3J0IHsgYXBpQ2FsbCB9O1xuLyogZXNsaW50LWVuYWJsZSBjb21wYXQvY29tcGF0ICovXG4iLCIvKiBnbG9iYWwgQWN0aXZlWE9iamVjdCAqL1xuXG5mdW5jdGlvbiBpc0lFKCkge1xuICBjb25zdCBteU5hdiA9IG5hdmlnYXRvci51c2VyQWdlbnQudG9Mb3dlckNhc2UoKTtcbiAgcmV0dXJuIG15TmF2LmluZGV4T2YoJ21zaWUnKSAhPT0gLTEgPyBwYXJzZUludChteU5hdi5zcGxpdCgnbXNpZScpWzFdKSA6IGZhbHNlO1xufVxuXG4vLyBhZGFwdGVkIChzdG9sZW4pIGZyb20gaHR0cHM6Ly9naXRodWIuY29tL3RvZGRtb3R0by9hdG9taWNcblxuZnVuY3Rpb24gcGFyc2UocmVxKSB7XG4gIHRyeSB7XG4gICAgcmV0dXJuIEpTT04ucGFyc2UocmVxLnJlc3BvbnNlVGV4dCk7XG4gIH0gY2F0Y2ggKGUpIHtcbiAgICByZXR1cm4gcmVxLnJlc3BvbnNlVGV4dDtcbiAgfVxufVxuXG4vLyBodHRwOi8vc3RhY2tvdmVyZmxvdy5jb20vYS8xNzE0ODk5XG5mdW5jdGlvbiB0b1F1ZXJ5U3RyaW5nKG9iaikge1xuICBjb25zdCBzdHIgPSBbXTtcbiAgZm9yIChjb25zdCBwIGluIG9iaikge1xuICAgIGlmIChvYmouaGFzT3duUHJvcGVydHkocCkpIHtcbiAgICAgIHN0ci5wdXNoKGAke2VuY29kZVVSSUNvbXBvbmVudChwKX09JHtlbmNvZGVVUklDb21wb25lbnQob2JqW3BdKX1gKTtcbiAgICB9XG4gIH1cbiAgcmV0dXJuIHN0ci5qb2luKCcmJyk7XG59XG5cbmZ1bmN0aW9uIG5vb3AoKSB7fVxuXG5mdW5jdGlvbiBtYWtlUmVxdWVzdChwYXJhbXMpIHtcbiAgY29uc3QgWE1MSHR0cFJlcXVlc3QgPSB3aW5kb3cuWE1MSHR0cFJlcXVlc3QgfHwgQWN0aXZlWE9iamVjdDtcbiAgY29uc3QgcmVxdWVzdCA9IG5ldyBYTUxIdHRwUmVxdWVzdCgnTVNYTUwyLlhNTEhUVFAuMy4wJyk7XG4gIHJlcXVlc3Qub3BlbihwYXJhbXMudHlwZSwgcGFyYW1zLnVybCwgdHJ1ZSk7XG4gIHJlcXVlc3Quc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC10eXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xuICByZXF1ZXN0Lm9ucmVhZHlzdGF0ZWNoYW5nZSA9IGZ1bmN0aW9uICgpIHtcbiAgICBpZiAocmVxdWVzdC5yZWFkeVN0YXRlID09PSA0KSB7XG4gICAgICBpZiAocmVxdWVzdC5zdGF0dXMgPj0gMjAwICYmIHJlcXVlc3Quc3RhdHVzIDwgMzAwKSB7XG4gICAgICAgIHBhcmFtcy5zdWNjZXNzKHBhcnNlKHJlcXVlc3QpKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIHBhcmFtcy5lcnJvcihwYXJzZShyZXF1ZXN0KSk7XG4gICAgICB9XG4gICAgfVxuICB9O1xuXG4gIHJlcXVlc3Quc2VuZChwYXJhbXMuZGF0YSk7XG59XG5cbi8qIElFOS1jb21wYXRpYmxlIHJlcXVlc3QgZnVuY3Rpb24uXG5cbklFOSBkb2VzIG5vdCBwZXJtaXQgY3Jvc3Mtb3JpZ2luIEhUVFAgcmVxdWVzdHMgaW4gdGhlIHVzdWFsIHdheS4gSXQgYWxzbyBkb2VzXG5ub3QgcGVybWl0IGEgcmVxdWVzdCB0byBiZSBtYWRlIHRvIGEgVVJJIHdpdGggYSBkaWZmZXJlbnQgcHJvdG9jb2wgZnJvbSB0aGF0XG5vZiB0aGUgcGFnZSwgZS5nLiBhbiBIVFRQUyByZXF1ZXN0IGZyb20gYW4gSFRUUCBwYWdlLlxuXG5UaGlzIGZ1bmN0aW9uIG1ha2VzIHJlcXVlc3RzIGluIGEgbWFubmVyIGNvbXBhdGlibGUgd2l0aCBJRTkncyBsaW1pdGF0aW9ucy5cbiovXG5mdW5jdGlvbiBtYWtlUmVxdWVzdElFKHBhcmFtcykge1xuICBjb25zdCByZXF1ZXN0ID0gbmV3IHdpbmRvdy5YRG9tYWluUmVxdWVzdCgpO1xuICBjb25zdCBwcm90b2NvbCA9IHdpbmRvdy5sb2NhdGlvbi5wcm90b2NvbDtcbiAgcGFyYW1zLnVybCA9IHBhcmFtcy51cmwucmVwbGFjZSgvaHR0cHM/Oi8sIHByb3RvY29sKTtcbiAgcmVxdWVzdC5vcGVuKHBhcmFtcy50eXBlLCBwYXJhbXMudXJsKTtcbiAgcmVxdWVzdC5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XG4gICAgcGFyYW1zLnN1Y2Nlc3MocGFyc2UocmVxdWVzdCkpO1xuICB9O1xuICByZXF1ZXN0Lm9uZXJyb3IgPSBmdW5jdGlvbiAoKSB7XG4gICAgcGFyYW1zLmVycm9yKHBhcnNlKHJlcXVlc3QpKTtcbiAgfTtcblxuICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICByZXF1ZXN0LnNlbmQocGFyYW1zLmRhdGEpO1xuICB9LCAwKTtcbn1cblxuZnVuY3Rpb24geGhyKG9wdGlvbnMpIHtcbiAgY29uc3QgcGFyYW1zID0ge1xuICAgIHR5cGU6IG9wdGlvbnMudHlwZSB8fCAnR0VUJyxcbiAgICBlcnJvcjogb3B0aW9ucy5lcnJvciB8fCBub29wLFxuICAgIHN1Y2Nlc3M6IG9wdGlvbnMuc3VjY2VzcyB8fCBub29wLFxuICAgIGRhdGE6IG9wdGlvbnMuZGF0YSxcbiAgICB1cmw6IG9wdGlvbnMudXJsIHx8ICcnLFxuICB9O1xuXG4gIGlmIChwYXJhbXMudHlwZSA9PT0gJ0dFVCcgJiYgcGFyYW1zLmRhdGEpIHtcbiAgICBwYXJhbXMudXJsID0gYCR7cGFyYW1zLnVybH0/JHt0b1F1ZXJ5U3RyaW5nKHBhcmFtcy5kYXRhKX1gO1xuICAgIGRlbGV0ZSBwYXJhbXMuZGF0YTtcbiAgfVxuXG4gIGlmIChpc0lFKCkgJiYgaXNJRSgpIDw9IDkpIHtcbiAgICBtYWtlUmVxdWVzdElFKHBhcmFtcyk7XG4gIH0gZWxzZSB7XG4gICAgbWFrZVJlcXVlc3QocGFyYW1zKTtcbiAgfVxufVxuXG5leHBvcnQgZGVmYXVsdCB4aHI7XG4iLCIvLyBUaGlzIHdpbGwgYmUgc3Vic3RpdHV0ZWQgd2l0aGluIHRoZSBidWlsZHNjcmlwdHMsIHNvIGRvbid0IGNoYW5nZSB0aGlzIVxuZXhwb3J0IGRlZmF1bHQgZnVuY3Rpb24gKCkge1xuICBjb25zdCBob3N0ID0gJyN7V2lkZ2V0QXBpLkhvc3R9JztcbiAgcmV0dXJuIGhvc3QuaW5kZXhPZignIycpID09PSAwID8gJ2h0dHBzOi8vd2lkZ2V0LnRwLXN0YWdpbmcuY29tJyA6IGhvc3Q7XG59XG4iLCJpbXBvcnQgeyBtYXBPYmplY3QsIHBpcGVNYXliZSwgcHJvbWlzZUFsbE9iamVjdCwgcHJvcCB9IGZyb20gJy4uLy4uL2ZuJztcbmltcG9ydCB7IGFwaUNhbGwgfSBmcm9tICcuLi9jYWxsJztcbmltcG9ydCB7IGdldE5leHRQYWdlTGlua3MgfSBmcm9tICcuL3V0aWwnO1xuaW1wb3J0IFJlc3BvbnNlUHJvY2Vzc29yIGZyb20gJy4vcmVzcG9uc2VQcm9jZXNzb3InO1xuXG5jb25zdCBOT19SRVZJRVdTX0VSUk9SID0gJ05vIHJldmlld3MgYXZhaWxhYmxlJztcblxuLyoqXG4gKiBUaGlzIGNsYXNzIHByb3ZpZGVzIHJldmlld3Mgb24gcmVxdWVzdCBvZiBhIGNvbnN1bWVyLiBJdCBjb2xsZWN0cyByZXZpZXdzXG4gKiB0aHJvdWdoIHBhZ2luYXRlZCBBUEkgY2FsbHMsIGFuZCB0aGVuIHByb3ZpZGVzIG9uZSBwYWdlIG9mIHJldmlld3Mgb24gcmVxdWVzdFxuICogZnJvbSB0aGUgY29uc3VtZXIuXG4gKlxuICogVGhyZWUgbWV0aG9kcyBhcmUgZXhwb3NlZCBhcyBpbnRlbmRlZCBmb3IgdXNlOiB7QGxpbmsgUmV2aWV3RmV0Y2hlciNjb25zdW1lUmV2aWV3c30sXG4gKiB7QGxpbmsgUmV2aWV3RmV0Y2hlciNwcm9kdWNlUmV2aWV3c30sIGFuZCB7QGxpbmsgUmV2aWV3RmV0Y2hlciNoYXNNb3JlUmV2aWV3c30uIE90aGVyXG4gKiBtZXRob2RzIHNob3VsZCBiZSBjb25zaWRlcmVkIHByaXZhdGUuXG4gKi9cbmNsYXNzIFJldmlld0ZldGNoZXIge1xuICAvKipcbiAgICogQ29uc3RydWN0IGEgUmV2aWV3RmV0Y2hlci5cbiAgICpcbiAgICogVGhlIGNvbnN0cnVjdG9yIHRha2VzIGFuIG9iamVjdCBjb250YWluaW5nIG9wdGlvbnMgYW5kIGRhdGEgcmVxdWlyZWQgdG9cbiAgICogb2J0YWluIGFuZCBwcm9kdWNlIHJldmlld3MgZm9yIGNvbnN1bXB0aW9uLlxuICAgKlxuICAgKiBAcGFyYW0ge09iamVjdH0gYXJncyAtIEFuIG9iamVjdCBjb250YWluaW5nIHRoZSBhcmd1bWVudHMgYmVsb3cuXG4gICAqIEBwYXJhbSB7bnVtYmVyfSBhcmdzLnJldmlld3NQZXJQYWdlIC0gVGhlIG51bWJlciBvZiByZXZpZXdzIHRvIHByb3ZpZGUgcGVyXG4gICAqIHJlcXVlc3QuXG4gICAqIEBwYXJhbSB7Ym9vbGVhbn0gYXJncy5pbmNsdWRlSW1wb3J0ZWRSZXZpZXdzIC0gV2hldGhlciB0byBpbmNsdWRlIGltcG9ydGVkXG4gICAqIHJldmlld3MgaW4gdGhlIHJldmlld3MgcHJvdmlkZWQuXG4gICAqIEBwYXJhbSB7T2JqZWN0fSBhcmdzLmJhc2VEYXRhIC0gVGhlIGJhc2VEYXRhIHJlc3BvbnNlIHJlY2VpdmVkIGZyb20gYSBiYXNlLWRhdGFcbiAgICogY2FsbC5cbiAgICogQHBhcmFtIHsuLi5PYmplY3R9IGFyZ3Mud3JhcEFyZ3MgLSBBbiBhcmJpdHJhcnkgc2V0IG9mIGFyZ3VtZW50cyB0byBhZGQgdG9cbiAgICogdGhlIGRhdGEgcHJvdmlkZWQgdG8gdGhlIGNhbGxiYWNrIGluIHtAbGluayBSZXZpZXdGZXRjaGVyI2NvbnN1bWVSZXZpZXdzfS5cbiAgICovXG4gIGNvbnN0cnVjdG9yKHsgcmV2aWV3c1BlclBhZ2UsIGluY2x1ZGVJbXBvcnRlZFJldmlld3MsIGJhc2VEYXRhLCAuLi53cmFwQXJncyB9KSB7XG4gICAgLy8gR2V0IG5leHQgcGFnZSBsaW5rcyBmcm9tIGEgYmFzZSBkYXRhIHJlc3BvbnNlLlxuICAgIGNvbnN0IGdldEJhc2VEYXRhTmV4dFBhZ2VMaW5rcyA9IGdldE5leHRQYWdlTGlua3MoKHJlc3BvbnNlS2V5KSA9PlxuICAgICAgcGlwZU1heWJlKHByb3AocmVzcG9uc2VLZXkpLCBwcm9wKCdsaW5rcycpLCBwcm9wKCduZXh0UGFnZScpKVxuICAgICk7XG5cbiAgICB0aGlzLnJldmlld3NQZXJQYWdlID0gcmV2aWV3c1BlclBhZ2U7XG4gICAgdGhpcy5pbmNsdWRlSW1wb3J0ZWRSZXZpZXdzID0gaW5jbHVkZUltcG9ydGVkUmV2aWV3cztcbiAgICB0aGlzLmJhc2VEYXRhID0gYmFzZURhdGE7XG4gICAgdGhpcy5uZXh0UGFnZSA9IGdldEJhc2VEYXRhTmV4dFBhZ2VMaW5rcyhiYXNlRGF0YSwgaW5jbHVkZUltcG9ydGVkUmV2aWV3cyk7XG4gICAgdGhpcy53cmFwQXJncyA9IHdyYXBBcmdzO1xuXG4gICAgdGhpcy5yZXZpZXdzID0gdGhpcy5fbWFrZVJlc3BvbnNlUHJvY2Vzc29yKGJhc2VEYXRhKS5nZXRSZXZpZXdzKCk7XG4gIH1cblxuICAvKipcbiAgICogQ29uc3VtZSBhIG51bWJlciBvZiByZXZpZXdzIHVzaW5nIGEgY2FsbGJhY2sgZnVuY3Rpb24uXG4gICAqXG4gICAqIFRoaXMgbWV0aG9kIGdldHMgb25lIHBhZ2Ugb2YgcmV2aWV3cywgYW5kIGNvbWJpbmVzIHRoaXMgd2l0aCB0aGUgZGF0YSBpblxuICAgKiB0aGUgd3JhcEFyZ3MgZmllbGQgYW5kIHBhc3NlcyBpdCBhbGwgdG8gYSBjYWxsYmFjay4gVGhlIHJldHVybiB2YWx1ZSBpc1xuICAgKiB3cmFwcGVkIGluIGFuIGFub255bW91cyBmdW5jdGlvbiB0byBtYWtlIGl0IHN1aXRhYmxlIGZvciB1c2Ugd2l0aGluIGV2ZW50XG4gICAqIGhhbmRsZXJzLlxuICAgKlxuICAgKiBAcGFyYW0ge0Z1bmN0aW9ufSBjYWxsYmFjayAtIEEgZnVuY3Rpb24gdG8gY2FsbCB3aXRoIGEgc2V0IG9mIHJldmlldyBkYXRhLlxuICAgKi9cbiAgY29uc3VtZVJldmlld3MoY2FsbGJhY2spIHtcbiAgICByZXR1cm4gKCkgPT5cbiAgICAgIHRoaXMucHJvZHVjZVJldmlld3MoKVxuICAgICAgICAudGhlbigocmV2aWV3cykgPT5cbiAgICAgICAgICBjYWxsYmFjayh7XG4gICAgICAgICAgICAuLi50aGlzLndyYXBBcmdzLFxuICAgICAgICAgICAgYmFzZURhdGE6IHRoaXMuYmFzZURhdGEsXG4gICAgICAgICAgICByZXZpZXdzLFxuICAgICAgICAgICAgaGFzTW9yZVJldmlld3M6IHRoaXMuaGFzTW9yZVJldmlld3MsXG4gICAgICAgICAgICBsb2FkTW9yZVJldmlld3M6IHRoaXMuY29uc3VtZVJldmlld3MuYmluZCh0aGlzKSxcbiAgICAgICAgICB9KVxuICAgICAgICApXG4gICAgICAgIC5jYXRjaCgoZXJyKSA9PiB7XG4gICAgICAgICAgaWYgKGVyciA9PT0gTk9fUkVWSUVXU19FUlJPUikge1xuICAgICAgICAgICAgcmV0dXJuIGNhbGxiYWNrKHtcbiAgICAgICAgICAgICAgLi4udGhpcy53cmFwQXJncyxcbiAgICAgICAgICAgICAgYmFzZURhdGE6IHRoaXMuYmFzZURhdGEsXG4gICAgICAgICAgICAgIHJldmlld3M6IFtdLFxuICAgICAgICAgICAgICBoYXNNb3JlUmV2aWV3czogZmFsc2UsXG4gICAgICAgICAgICAgIGxvYWRNb3JlUmV2aWV3czogdGhpcy5jb25zdW1lUmV2aWV3cy5iaW5kKHRoaXMpLFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIC8vIFJldGhyb3cgZXJyb3Igd2hpY2ggaXMgdW5leHBlY3RlZFxuICAgICAgICAgICAgdGhyb3cgZXJyO1xuICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gIH1cblxuICAvKipcbiAgICogUHJvZHVjZSBhIG51bWJlciBvZiByZXZpZXdzLlxuICAgKlxuICAgKiBUaGlzIG1ldGhvZCBwcm9kdWNlcyBvbmUgcGFnZSBvZiByZXZpZXdzLiBJdCBtYXkgcmVxdWlyZSB0byBmZXRjaCBhZGRpdGlvbmFsXG4gICAqIHJldmlld3MgZnJvbSBhbiBBUEkgaWYgdGhlcmUgYXJlIGluc3VmZmljZW50IHJldmlld3MgYXZhaWxhYmxlIGxvY2FsbHkuIFRoZVxuICAgKiByZXZpZXdzIGFyZSB0aHVzIHJldHVybmVkIHdyYXBwZWQgaW4gYSBQcm9taXNlLlxuICAgKi9cbiAgcHJvZHVjZVJldmlld3MoKSB7XG4gICAgY29uc3QgcHJvY2Vzc1Jlc3BvbnNlID0gKHJlc3BvbnNlKSA9PiB7XG4gICAgICBjb25zdCByZXNwb25zZVByb2Nlc3NvciA9IHRoaXMuX21ha2VSZXNwb25zZVByb2Nlc3NvcihyZXNwb25zZSk7XG4gICAgICB0aGlzLm5leHRQYWdlID0gcmVzcG9uc2VQcm9jZXNzb3IuZ2V0TmV4dFBhZ2VMaW5rcygpO1xuICAgICAgdGhpcy5yZXZpZXdzLnB1c2goLi4ucmVzcG9uc2VQcm9jZXNzb3IuZ2V0UmV2aWV3cygpKTtcbiAgICAgIHJldHVybiB0aGlzLl90YWtlUmV2aWV3cygpO1xuICAgIH07XG5cbiAgICBpZiAodGhpcy5yZXZpZXdzLmxlbmd0aCA9PT0gMCkge1xuICAgICAgLy8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lIGNvbXBhdC9jb21wYXRcbiAgICAgIHJldHVybiBQcm9taXNlLnJlamVjdChOT19SRVZJRVdTX0VSUk9SKTtcbiAgICB9XG4gICAgcmV0dXJuIHRoaXMucmV2aWV3c1BlclBhZ2UgPj0gdGhpcy5yZXZpZXdzLmxlbmd0aFxuICAgICAgPyB0aGlzLl9mZXRjaFJldmlld3MoKS50aGVuKHByb2Nlc3NSZXNwb25zZSlcbiAgICAgIDogLy8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lIGNvbXBhdC9jb21wYXRcbiAgICAgICAgUHJvbWlzZS5yZXNvbHZlKHRoaXMuX3Rha2VSZXZpZXdzKCkpO1xuICB9XG5cbiAgLyoqXG4gICAqIEZsYWcgd2hldGhlciBtb3JlIHJldmlld3MgYXJlIGF2YWlsYWJsZSBmb3IgY29uc3VtcHRpb24uXG4gICAqXG4gICAqIFdoZXJlIHRydWUsIHRoaXMgbWVhbnMgaXQgaXMgcG9zc2libGUgdG8gbG9hZCBtb3JlIHJldmlld3MuIElmIGZhbHNlLCBub1xuICAgKiBtb3JlIHJldmlld3MgYXJlIGF2YWlsYWJsZS5cbiAgICovXG4gIGdldCBoYXNNb3JlUmV2aWV3cygpIHtcbiAgICByZXR1cm4gdGhpcy5yZXZpZXdzLmxlbmd0aCA+IDA7XG4gIH1cblxuICAvLyBQcml2YXRlIE1ldGhvZHMgLy9cblxuICAvKipcbiAgICogVGFrZSBhIHBhZ2Ugb2YgcmV2aWV3cyBmcm9tIGludGVybmFsIGNhY2hlIG9mIHJldmlld3MsIHJlbW92aW5nIHRoZXNlIGFuZFxuICAgKiByZXR1cm5pbmcgdGhlbSBmcm9tIHRoZSBtZXRob2QuXG4gICAqL1xuICBfdGFrZVJldmlld3MoKSB7XG4gICAgcmV0dXJuIHRoaXMucmV2aWV3cy5zcGxpY2UoMCwgdGhpcy5yZXZpZXdzUGVyUGFnZSk7XG4gIH1cblxuICAvKipcbiAgICogRmV0Y2ggbW9yZSByZXZpZXdzIGZyb20gdGhlIEFQSS5cbiAgICovXG4gIF9mZXRjaFJldmlld3MoKSB7XG4gICAgcmV0dXJuIHByb21pc2VBbGxPYmplY3QobWFwT2JqZWN0KGFwaUNhbGwsIHRoaXMubmV4dFBhZ2UpKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBDb25zdHJ1Y3QgYSB7QGxpbmsgUmVzcG9uc2VQcm9jZXNzb3J9IGluc3RhbmNlIHVzaW5nIHByb3BlcnRpZXMgZnJvbSB0aGlzIGluc3RhbmNlLlxuICAgKi9cbiAgX21ha2VSZXNwb25zZVByb2Nlc3NvcihyZXNwb25zZSkge1xuICAgIHJldHVybiBuZXcgUmVzcG9uc2VQcm9jZXNzb3IocmVzcG9uc2UsIHtcbiAgICAgIGluY2x1ZGVJbXBvcnRlZFJldmlld3M6IHRoaXMuaW5jbHVkZUltcG9ydGVkUmV2aWV3cyxcbiAgICAgIGRpc3BsYXlOYW1lOiB0aGlzLmJhc2VEYXRhLmJ1c2luZXNzRW50aXR5LmRpc3BsYXlOYW1lLFxuICAgIH0pO1xuICB9XG59XG5cbmV4cG9ydCBkZWZhdWx0IFJldmlld0ZldGNoZXI7XG4iLCIvLyBDb252ZXJ0IHJlZHVjZSBtZXRob2QgaW50byBhIGZ1bmN0aW9uXG5jb25zdCByZWR1Y2UgPSAoZikgPT4gKGluaXQpID0+ICh4cykgPT4geHMucmVkdWNlKGYsIGluaXQpO1xuXG4vLyBDb252ZXJ0IGZpbHRlciBtZXRob2QgaW50byBhIGZ1bmN0aW9uXG5jb25zdCBmaWx0ZXIgPSAocCkgPT4gKHhzKSA9PiB4cy5maWx0ZXIocCk7XG5cbi8vIENvbnZlcnQgbWFwIG1ldGhvZCBpbnRvIGEgZnVuY3Rpb25cbmNvbnN0IG1hcCA9IChmKSA9PiAoeHMpID0+IHhzLm1hcChmKTtcblxuLy8gSW1wbGVtZW50YXRpb24gb2YgbWFwLCBidXQgZm9yIGFuIG9iamVjdC4gVmFsdWVzIGFyZSByZXBsYWNlZCB3aXRoIHRoZSByZXN1bHRcbi8vIG9mIGNhbGxpbmcgdGhlIHBhc3NlZCBmdW5jdGlvbiBvZiB0aGVtOyBrZXlzIHJlbWFpbiB1bmNoYW5nZWQuXG5jb25zdCBtYXBPYmplY3QgPSAoZiwgb2JqKSA9PiBPYmplY3Qua2V5cyhvYmopLnJlZHVjZSgoYWxsLCBrKSA9PiAoeyAuLi5hbGwsIFtrXTogZihvYmpba10pIH0pLCB7fSk7XG5cbi8vIFRyYW5zZm9ybXMgYW4gb2JqZWN0IGNvbnRhaW5pbmcgYXJiaXRyYXJ5IGtleXMsIGFuZCBwcm9taXNlIHZhbHVlcywgaW50byBhXG4vLyBwcm9taXNlLXdyYXBwZWQgb2JqZWN0LCB3aXRoIHRoZSBzYW1lIGtleXMgYW5kIHRoZSByZXN1bHQgb2YgcmVzb2x2aW5nIGVhY2hcbi8vIHByb21pc2UgYXMgdmFsdWVzLlxuY29uc3QgcHJvbWlzZUFsbE9iamVjdCA9IChvYmopID0+IHtcbiAgY29uc3Qga2V5cyA9IE9iamVjdC5rZXlzKG9iaik7XG4gIGNvbnN0IHZhbHVlcyA9IGtleXMubWFwKChrKSA9PiBvYmpba10pO1xuICAvLyBlc2xpbnQtZGlzYWJsZS1uZXh0LWxpbmUgY29tcGF0L2NvbXBhdFxuICByZXR1cm4gUHJvbWlzZS5hbGwodmFsdWVzKS50aGVuKChwcm9taXNlcykgPT5cbiAgICBwcm9taXNlcy5yZWR1Y2UoKGFsbCwgcHJvbWlzZSwgaWR4KSA9PiAoeyAuLi5hbGwsIFtrZXlzW2lkeF1dOiBwcm9taXNlIH0pLCB7fSlcbiAgKTtcbn07XG5cbi8qKlxuICogQ29udmVydCBhbiBhcnJheSBjb250YWluaW5nIHBhaXJzIG9mIHZhbHVlcyBpbnRvIGFuIG9iamVjdC5cbiAqXG4gKiAgIFtbazEsIHYxXSwgW2syLCB2Ml0sIC4uLiBdIC0+IHsgW2sxXTogdjEsIFtrMl06IHYyLCAuLi4gfVxuICovXG5jb25zdCBwYWlyc1RvT2JqZWN0ID0gKHBhaXJzKSA9PiBwYWlycy5yZWR1Y2UoKG9iaiwgW2ssIHZdKSA9PiAoeyAuLi5vYmosIFtrXTogdiB9KSwge30pO1xuXG5jb25zdCBpc051bGxhcnkgPSAodmFsdWUpID0+IHR5cGVvZiB2YWx1ZSA9PT0gJ3VuZGVmaW5lZCcgfHwgdmFsdWUgPT09IG51bGw7XG5cbmNvbnN0IGlzTnVsbGFyeU9yRmFsc2UgPSAodmFsdWUpID0+IGlzTnVsbGFyeSh2YWx1ZSkgfHwgdmFsdWUgPT09IGZhbHNlO1xuXG4vLyBGaWx0ZXIgb3V0IGFsbCBudWxsIG9yIHVuZGVmaW5lZCB2YWx1ZXMgZnJvbSBhbiBvYmplY3QuXG5jb25zdCByZWplY3ROdWxsYXJ5VmFsdWVzID0gKG9iaikgPT4ge1xuICByZXR1cm4gT2JqZWN0LmtleXMob2JqKS5yZWR1Y2UoXG4gICAgKG5ld09iaiwga2V5KSA9PiAoe1xuICAgICAgLi4ubmV3T2JqLFxuICAgICAgLi4uKGlzTnVsbGFyeShvYmpba2V5XSkgPyB7fSA6IHsgW2tleV06IG9ialtrZXldIH0pLFxuICAgIH0pLFxuICAgIHt9XG4gICk7XG59O1xuXG4vKipcbiAqIFNwbGl0IGFuIGFycmF5IG9mIHZhbHVlcyBpbnRvIGNodW5rcyBvZiBhIGdpdmVuIHNpemUuXG4gKlxuICogSWYgdGhlIG51bWJlciBvZiB2YWx1ZXMgZG9lcyBub3QgZGl2aWRlIGV2ZW5seSBpbnRvIHRoZSBjaHVuayBzaXplLCB0aGVcbiAqIGZpbmFsIGNodW5rIHdpbGwgYmUgc21hbGxlciB0aGFuIGNodW5rU2l6ZS5cbiAqXG4gKiAgIGNodW5rIDIgW2EsIGIsIGMsIGQsIGUsIGYsIGddIC0+IFtbYSwgYl0sIFtjLCBkXSwgW2UsIGZdLCBbZ11dXG4gKi9cbmNvbnN0IGNodW5rID0gKGNodW5rU2l6ZSkgPT5cbiAgcmVkdWNlKChjaHVua3MsIHZhbCwgaWR4KSA9PiB7XG4gICAgY29uc3QgbGFzdENodW5rID0gY2h1bmtzW2NodW5rcy5sZW5ndGggLSAxXTtcbiAgICBjb25zdCBpc05ld0NodW5rID0gaWR4ICUgY2h1bmtTaXplID09PSAwO1xuICAgIGNvbnN0IG5ld0NodW5rID0gaXNOZXdDaHVuayA/IFt2YWxdIDogWy4uLmxhc3RDaHVuaywgdmFsXTtcbiAgICByZXR1cm4gWy4uLmNodW5rcy5zbGljZSgwLCBjaHVua3MubGVuZ3RoIC0gKGlzTmV3Q2h1bmsgPyAwIDogMSkpLCBuZXdDaHVua107XG4gIH0pKFtdKTtcblxuLyoqXG4gKiBTcGxpdCBhbiBhcnJheSBvZiB2YWx1ZXMgaW50byBjaHVua3Mgb2YgYSBnaXZlbiBzaXplLCBhbmQgdGhlbiB0cmFuc3Bvc2UgdmFsdWVzLlxuICpcbiAqIFRoaXMgaXMgZXF1aXZhbGVudCB0byB7QGxpbmsgJ2NodW5rJ30sIGJ1dCB3aXRoIHRoZSB2YWx1ZXMgdHJhbnNwb3NlZDpcbiAqXG4gKiAgIGNodW5rVHJhbnNwb3NlIDIgW2EsIGIsIGMsIGQsIGUsIGYsIGddIC0+IFtbYSwgYywgZSwgZ10sIFtiLCBkLCBmXV1cbiAqXG4gKiBUaGUgdHJhbnNwb3NpdGlvbiBoYXMgdGhlIGVmZmVjdCBvZiB0dXJuaW5nIGFuIGFycmF5IG9mIHggY2h1bmtzIG9mIHNpemUgbixcbiAqIGludG8gYW4gYXJyYXkgb2YgbiBjaHVua3Mgb2Ygc2l6ZSB4LlxuICovXG5jb25zdCBjaHVua1RyYW5zcG9zZSA9IChjaHVua1NpemUpID0+XG4gIHJlZHVjZSgoY2h1bmtzLCB2YWwsIGlkeCkgPT4ge1xuICAgIGNvbnN0IGNodW5rSWR4ID0gaWR4ICUgY2h1bmtTaXplO1xuICAgIGNvbnN0IG5ld0NodW5rID0gWy4uLihjaHVua3NbY2h1bmtJZHhdIHx8IFtdKSwgdmFsXTtcbiAgICByZXR1cm4gWy4uLmNodW5rcy5zbGljZSgwLCBjaHVua0lkeCksIG5ld0NodW5rLCAuLi5jaHVua3Muc2xpY2UoY2h1bmtJZHggKyAxKV07XG4gIH0pKFtdKTtcblxuLyoqXG4gKiBDb21wb3NlIGEgc2VyaWVzIG9mIGZ1bmN0aW9ucyB0b2dldGhlci5cbiAqXG4gKiBFcXVpdmFsZW50IHRvIGFwcGx5aW5nIGFuIGFycmF5IG9mIGZ1bmN0aW9ucyB0byBhIHZhbHVlLCByaWdodCB0byBsZWZ0LlxuICpcbiAqICAgY29tcG9zZShmLCBnLCBoKSh4KSA9PT0gZihnKGgoeCkpKVxuICpcbiAqL1xuY29uc3QgY29tcG9zZSA9XG4gICguLi5mcykgPT5cbiAgKHgpID0+XG4gICAgZnMucmVkdWNlUmlnaHQoKHZhbCwgZikgPT4gZih2YWwpLCB4KTtcblxuLy8gUGlwZSBhIHZhbHVlIHRocm91Z2ggYSBzZXJpZXMgb2YgZnVuY3Rpb25zIHdoaWNoIHRlcm1pbmF0ZXMgaW1tZWRpYXRlbHkgb25cbi8vIHJlY2VpdmluZyBhIG51bGxhcnkgdmFsdWUuXG5jb25zdCBwaXBlTWF5YmUgPVxuICAoLi4uZnMpID0+XG4gICh4KSA9PlxuICAgIGZzLnJlZHVjZSgodmFsLCBmKSA9PiAoaXNOdWxsYXJ5KHZhbCkgPyB2YWwgOiBmKHZhbCkpLCB4KTtcblxuLy8gR2V0IGZpcnN0IHZhbHVlIGZyb20gYW4gYXJyYXlcbmNvbnN0IGZpcnN0ID0gKFt4XSkgPT4geDtcblxuLy8gRmlyc3QgZmlyc3QgdmFsdWUgbWF0Y2hpbmcgcHJlZGljYXRlIHAgaW4gYW4gYXJyYXkgb2YgdmFsdWVzLlxuY29uc3QgZmluZCA9IChwKSA9PiBwaXBlTWF5YmUoZmlsdGVyKHApLCBmaXJzdCk7XG5cbi8vIEdldCBhIHZhbHVlIGZyb20gYW4gb2JqZWN0IGF0IGEgZ2l2ZW4ga2V5LlxuY29uc3QgcHJvcCA9XG4gIChrKSA9PlxuICAob2JqID0ge30pID0+XG4gICAgb2JqW2tdO1xuXG4vLyBHZXQgYSB2YWx1ZSBmcm9tIGFuIG9iamVjdCBhdCBhIGdpdmVuIGtleSBpZiBpdCBleGlzdHMuXG5jb25zdCBwcm9wTWF5YmUgPVxuICAoaykgPT5cbiAgKG9iaiA9IHt9KSA9PlxuICAgIG9ialtrXSB8fCBvYmo7XG5cbi8vIFRlc3QgaWYgYSB2YWx1ZSBpcyBmYWxzZSBvciBudWxsYXJ5LCByZXR1cm5pbmcgbnVsbCBpZiB0cnVlLCBvciBhIHNlY29uZCB2YWx1ZSBpZiBmYWxzZS5cbi8vIEludGVuZGVkIGZvciB1c2Ugd2l0aGluIGEgcGlwZU1heWJlIHdoZXJlIHlvdSB3YW50IHRvIHRlcm1pbmF0ZSBleGVjdXRpb24gd2hlcmVcbi8vIGFuIGFyYml0cmFyeSB2YWx1ZSBpcyBudWxsLlxuY29uc3QgZ3VhcmQgPSAocCkgPT4gKHgpID0+IGlzTnVsbGFyeU9yRmFsc2UocCkgPyBudWxsIDogeDtcblxuZXhwb3J0IHtcbiAgY2h1bmssXG4gIGNodW5rVHJhbnNwb3NlLFxuICBjb21wb3NlLFxuICBmaWx0ZXIsXG4gIGZpbmQsXG4gIGZpcnN0LFxuICBndWFyZCxcbiAgbWFwLFxuICBtYXBPYmplY3QsXG4gIHBhaXJzVG9PYmplY3QsXG4gIHBpcGVNYXliZSxcbiAgcHJvbWlzZUFsbE9iamVjdCxcbiAgcHJvcCxcbiAgcHJvcE1heWJlLFxuICByZWplY3ROdWxsYXJ5VmFsdWVzLFxufTtcbiIsImltcG9ydCB7IGd1YXJkLCBwaXBlTWF5YmUsIHJlamVjdE51bGxhcnlWYWx1ZXMgfSBmcm9tICcuLi8uLi9mbic7XG5cbi8qKlxuICogR2V0IG5leHQgcGFnZSBsaW5rcyBmcm9tIGEgcmVzcG9uc2UuXG4gKlxuICogVGhpcyBmdW5jdGlvbiB0YWtlIGEgZ2V0dGVyIGZ1bmN0aW9uLCB1c2VkIHRvIGV4dHJhY3QgYSBwYXJ0aWN1bGFyIHR5cGUgb2YgbGluayxcbiAqIGVpdGhlciBmb3IgcHJvZHVjdFJldmlld3Mgb3IgaW1wb3J0ZWRQcm9kdWN0UmV2aWV3cy4gSXQgcmV0dXJucyBhIGZ1bmN0aW9uIHdoaWNoXG4gKiB0YWtlIGEgcmVzcG9uc2UgYW5kIGEgZmxhZyB0byBpbmRpY2F0ZSB3aGV0aGVyIHRvIGluY2x1ZGUgaW1wb3J0ZWQgcmV2aWV3cy4gVGhpc1xuICogY2FuIHRoZW4gYmUgY2FsbGVkIHRvIG9idGFpbiBhdmFpbGFibGUgbmV4dCBwYWdlIGxpbmtzLlxuICovXG5jb25zdCBnZXROZXh0UGFnZUxpbmtzID0gKGdldHRlcikgPT4gKHJlc3BvbnNlLCBpbmNsdWRlSW1wb3J0ZWRSZXZpZXdzID0gZmFsc2UpID0+IHtcbiAgY29uc3QgcHJvZHVjdFJldmlld3MgPSBnZXR0ZXIoJ3Byb2R1Y3RSZXZpZXdzJykocmVzcG9uc2UpO1xuICBjb25zdCBpbXBvcnRlZFByb2R1Y3RSZXZpZXdzID0gcGlwZU1heWJlKFxuICAgIGd1YXJkKGluY2x1ZGVJbXBvcnRlZFJldmlld3MpLFxuICAgIGdldHRlcignaW1wb3J0ZWRQcm9kdWN0UmV2aWV3cycpXG4gICkocmVzcG9uc2UpO1xuICByZXR1cm4gcmVqZWN0TnVsbGFyeVZhbHVlcyh7XG4gICAgcHJvZHVjdFJldmlld3MsXG4gICAgaW1wb3J0ZWRQcm9kdWN0UmV2aWV3cyxcbiAgfSk7XG59O1xuXG5leHBvcnQgeyBnZXROZXh0UGFnZUxpbmtzIH07XG4iLCJpbXBvcnQgeyBmaW5kLCBndWFyZCwgbWFwLCBwaXBlTWF5YmUsIHByb3AsIHByb3BNYXliZSB9IGZyb20gJy4uLy4uL2ZuJztcbmltcG9ydCB7IGdldE5leHRQYWdlTGlua3MgfSBmcm9tICcuL3V0aWwnO1xuXG4vKipcbiAqIFRoaXMgY2xhc3MgcHJvY2Vzc2VzIGFuIEFQSSByZXNwb25zZSBjb250YWluaW5nIHJldmlld3MgYW5kIHBhZ2luYXRpb25cbiAqIGRhdGEuXG4gKi9cbmNsYXNzIFJldmlld1Jlc3BvbnNlUHJvY2Vzc29yIHtcbiAgLyoqXG4gICAqIENyZWF0ZSBhIFJldmlld1Jlc3BvbnNlUHJvY2Vzc29yIGluc3RhbmNlLlxuICAgKlxuICAgKiBUYWtlcyBhbiBBUEkgcmVzcG9uc2Ugb2JqZWN0IGZvciBwcm9jZXNzaW5nLCB0b2dldGhlciB3aXRoIGEgc2hvcnQgbGlzdFxuICAgKiBvZiBvcHRpb25zIGZvciBwcm9jZXNzaW5nIGFuZCBhbm5vdGF0aW5nIHJldmlld3MuXG4gICAqL1xuICBjb25zdHJ1Y3RvcihyZXNwb25zZSwgeyBpbmNsdWRlSW1wb3J0ZWRSZXZpZXdzLCBkaXNwbGF5TmFtZSB9KSB7XG4gICAgdGhpcy5yZXNwb25zZSA9IHJlc3BvbnNlO1xuICAgIHRoaXMuaW5jbHVkZUltcG9ydGVkUmV2aWV3cyA9IGluY2x1ZGVJbXBvcnRlZFJldmlld3M7XG4gICAgdGhpcy5kaXNwbGF5TmFtZSA9IGRpc3BsYXlOYW1lO1xuICB9XG5cbiAgLyoqXG4gICAqIEdldCBhIGNvbWJpbmVkIGxpc3Qgb2YgcmV2aWV3cyBmcm9tIHRoZSBBUEkgcmVzcG9uc2UuXG4gICAqXG4gICAqIFRoaXMgbWV0aG9kIGV4dHJhY3RzIGFsbCByZXZpZXdzIGZyb20gdGhlIHJlc3BvbnNlLCBpbmNsdWRpbmcgb3B0aW9uYWxseVxuICAgKiBpbXBvcnRlZCByZXZpZXdzLCBhbmQgdGhlbiBjb21iaW5lcyBhbmQgc29ydHMgdGhlc2UgYnkgZGF0ZSwgZGVzY2VuZGluZy5cbiAgICovXG4gIGdldFJldmlld3MoKSB7XG4gICAgY29uc3QgeyBwcm9kdWN0UmV2aWV3cywgaW1wb3J0ZWRQcm9kdWN0UmV2aWV3cyB9ID0gdGhpcy5yZXNwb25zZTtcbiAgICBjb25zdCBvcmRlckJ5Q3JlYXRlZEF0RGVzYyA9ICh7IGNyZWF0ZWRBdDogYzEgfSwgeyBjcmVhdGVkQXQ6IGMyIH0pID0+XG4gICAgICBuZXcgRGF0ZShjMikgLSBuZXcgRGF0ZShjMSk7XG4gICAgY29uc3QgcHJvZHVjdFJldmlld3NMaXN0ID1cbiAgICAgIHBpcGVNYXliZShwcm9wTWF5YmUoJ3Byb2R1Y3RSZXZpZXdzJyksIHByb3BNYXliZSgncmV2aWV3cycpKShwcm9kdWN0UmV2aWV3cykgfHwgW107XG5cbiAgICBjb25zdCBpbXBvcnRlZFJldmlld3NMaXN0ID1cbiAgICAgIHBpcGVNYXliZShcbiAgICAgICAgZ3VhcmQodGhpcy5pbmNsdWRlSW1wb3J0ZWRSZXZpZXdzKSxcbiAgICAgICAgcHJvcE1heWJlKCdpbXBvcnRlZFByb2R1Y3RSZXZpZXdzJyksXG4gICAgICAgIHByb3BNYXliZSgncHJvZHVjdFJldmlld3MnKSxcbiAgICAgICAgbWFwKChyZXZpZXcpID0+ICh7XG4gICAgICAgICAgLi4ucmV2aWV3LFxuICAgICAgICAgIHZlcmlmaWVkQnk6IHJldmlldy50eXBlID09PSAnRXh0ZXJuYWwnXG4gICAgICAgICAgICA/IHJldmlldy5zb3VyY2VcbiAgICAgICAgICAgICAgPyByZXZpZXcuc291cmNlLm5hbWVcbiAgICAgICAgICAgICAgOiB0aGlzLmRpc3BsYXlOYW1lXG4gICAgICAgICAgICA6IHRoaXMuZGlzcGxheU5hbWUsXG4gICAgICAgIH0pKSxcbiAgICAgICkoaW1wb3J0ZWRQcm9kdWN0UmV2aWV3cykgfHwgW107XG5cbiAgICByZXR1cm4gWy4uLnByb2R1Y3RSZXZpZXdzTGlzdCwgLi4uaW1wb3J0ZWRSZXZpZXdzTGlzdF0uc29ydChvcmRlckJ5Q3JlYXRlZEF0RGVzYyk7XG4gIH1cblxuICAvKipcbiAgICogR2V0IGFuIG9iamVjdCBjb250YWluaW5nIGxpbmtzIHRvIHRoZSBuZXh0IHBhZ2UgVVJJcyBjb250YWluZWQgd2l0aGluIHRoZVxuICAgKiBBUEkgcmVzcG9uc2UuXG4gICAqL1xuICBnZXROZXh0UGFnZUxpbmtzKCkge1xuICAgIC8vIEdldCBuZXh0IHBhZ2UgbGlua3MgZnJvbSBhIHBhZ2luYXRpb24gcmVzcG9uc2UuXG4gICAgY29uc3QgZ2V0T2xkUGFnaW5hdGlvbk5leHRQYWdlTGlua3MgPSBnZXROZXh0UGFnZUxpbmtzKChyZXNwb25zZUtleSkgPT5cbiAgICAgIHBpcGVNYXliZShcbiAgICAgICAgcHJvcChyZXNwb25zZUtleSksXG4gICAgICAgIHByb3AoJ2xpbmtzJyksXG4gICAgICAgIGZpbmQoKGxpbmspID0+IGxpbmsucmVsID09PSAnbmV4dC1wYWdlJyksXG4gICAgICAgIHByb3AoJ2hyZWYnKSxcbiAgICAgICksXG4gICAgKTtcblxuICAgIGNvbnN0IGdldE5ld1BhZ2luYXRpb25OZXh0UGFnZUxpbmtzID0gZ2V0TmV4dFBhZ2VMaW5rcygocmVzcG9uc2VLZXkpID0+XG4gICAgICBwaXBlTWF5YmUocHJvcChyZXNwb25zZUtleSksIHByb3AocmVzcG9uc2VLZXkpLCBwcm9wKCdsaW5rcycpLCBwcm9wKCduZXh0UGFnZScpKSxcbiAgICApO1xuXG4gICAgY29uc3QgbmV3TGlua3MgPSBnZXROZXdQYWdpbmF0aW9uTmV4dFBhZ2VMaW5rcyh0aGlzLnJlc3BvbnNlLCB0aGlzLmluY2x1ZGVJbXBvcnRlZFJldmlld3MpO1xuICAgIGNvbnN0IG9sZExpbmtzID0gZ2V0T2xkUGFnaW5hdGlvbk5leHRQYWdlTGlua3ModGhpcy5yZXNwb25zZSwgdGhpcy5pbmNsdWRlSW1wb3J0ZWRSZXZpZXdzKTtcblxuICAgIHJldHVybiB7IC4uLm9sZExpbmtzLCAuLi5uZXdMaW5rcyB9O1xuICB9XG59XG5cbmV4cG9ydCBkZWZhdWx0IFJldmlld1Jlc3BvbnNlUHJvY2Vzc29yO1xuIiwiaW1wb3J0IHsgYWRkRXZlbnRMaXN0ZW5lciB9IGZyb20gJy4vdXRpbHMuanMnO1xuXG5jb25zdCB3cGFyZW50ID0gd2luZG93LnBhcmVudDtcbmNvbnN0IG1lc3NhZ2VRdWV1ZSA9IFtdO1xuY29uc3QgZGVmYXVsdE9wdGlvbnMgPSB7XG4gIGNvbW1hbmQ6ICdjcmVhdGVJRnJhbWUnLFxuICBwb3NpdGlvbjogJ2NlbnRlciB0b3AnLFxuICBzaG93OiBmYWxzZSxcbiAgc291cmNlOiAncG9wdXAuaHRtbCcsXG4gIHF1ZXJ5U3RyaW5nOiAnJyxcbn07XG5jb25zdCBwb3B1cE9wdGlvbnMgPSB7XG4gIG5hbWU6ICdwb3B1cCcsXG4gIG1vZGFsOiBmYWxzZSxcbiAgc3R5bGVzOiB7XG4gICAgaGVpZ2h0OiAnMzAwcHgnLFxuICAgIHdpZHRoOiAnJyxcbiAgfSxcbn07XG5jb25zdCBtb2RhbE9wdGlvbnMgPSB7XG4gIG5hbWU6ICdtb2RhbCcsXG4gIG1vZGFsOiB0cnVlLFxuICBzdHlsZXM6IHtcbiAgICB3aWR0aDogJzEwMCUnLFxuICAgIGhlaWdodDogJzEwMCUnLFxuICAgIHBvc2l0aW9uOiAnZml4ZWQnLFxuICAgIGxlZnQ6ICcwJyxcbiAgICByaWdodDogJzAnLFxuICAgIHRvcDogJzAnLFxuICAgIGJvdHRvbTogJzAnLFxuICAgIG1hcmdpbjogJzAgYXV0bycsXG4gICAgemluZGV4OiA5OSxcbiAgfSxcbn07XG5cbmxldCBpZCA9IG51bGw7XG5jb25zdCBsaXN0ZW5lckNhbGxiYWNrcyA9IFtdO1xuXG5mdW5jdGlvbiBzZW5kTWVzc2FnZShtZXNzYWdlKSB7XG4gIGlmIChpZCkge1xuICAgIG1lc3NhZ2Uud2lkZ2V0SWQgPSBpZDtcbiAgICBtZXNzYWdlID0gSlNPTi5zdHJpbmdpZnkobWVzc2FnZSk7IC8vIFRoaXMgaXMgdG8gbWFrZSBpdCBJRTggY29tcGF0aWJsZVxuICAgIHdwYXJlbnQucG9zdE1lc3NhZ2UobWVzc2FnZSwgJyonKTtcbiAgfSBlbHNlIHtcbiAgICBtZXNzYWdlUXVldWUucHVzaChtZXNzYWdlKTtcbiAgfVxufVxuXG5mdW5jdGlvbiBzZW5kTWVzc2FnZVRvKHRhcmdldCkge1xuICByZXR1cm4gKG1lc3NhZ2UsIHBheWxvYWQgPSB7fSkgPT5cbiAgICBzZW5kTWVzc2FnZSh7XG4gICAgICAuLi5wYXlsb2FkLFxuICAgICAgbWVzc2FnZSxcbiAgICAgIGNvbW1hbmQ6ICdtZXNzYWdlJyxcbiAgICAgIG5hbWU6IHRhcmdldCxcbiAgICB9KTtcbn1cblxuZnVuY3Rpb24gc2VuZFF1ZXVlKCkge1xuICB3aGlsZSAobWVzc2FnZVF1ZXVlLmxlbmd0aCkge1xuICAgIHNlbmRNZXNzYWdlKG1lc3NhZ2VRdWV1ZS5wb3AoKSk7XG4gIH1cbn1cblxuZnVuY3Rpb24gY3JlYXRlUG9wdXBJZnJhbWUob3B0aW9ucykge1xuICBzZW5kTWVzc2FnZSh7XG4gICAgLi4uZGVmYXVsdE9wdGlvbnMsXG4gICAgLi4ucG9wdXBPcHRpb25zLFxuICAgIC4uLm9wdGlvbnMsXG4gIH0pO1xufVxuXG5mdW5jdGlvbiBjcmVhdGVNb2RhbElmcmFtZShvcHRpb25zKSB7XG4gIHNlbmRNZXNzYWdlKHtcbiAgICAuLi5kZWZhdWx0T3B0aW9ucyxcbiAgICAuLi5tb2RhbE9wdGlvbnMsXG4gICAgLi4ub3B0aW9ucyxcbiAgfSk7XG59XG5cbmZ1bmN0aW9uIHNldFN0eWxlcyhzdHlsZXMsIG9wdGlvbmFsSWZyYW1lTmFtZSkge1xuICBzZW5kTWVzc2FnZSh7IGNvbW1hbmQ6ICdzZXRTdHlsZScsIG5hbWU6IG9wdGlvbmFsSWZyYW1lTmFtZSwgc3R5bGU6IHN0eWxlcyB9KTtcbn1cblxuZnVuY3Rpb24gc2hvd0lmcmFtZShpZnJhbWVOYW1lKSB7XG4gIHNlbmRNZXNzYWdlKHsgY29tbWFuZDogJ3Nob3cnLCBuYW1lOiBpZnJhbWVOYW1lIH0pO1xuICBzZW5kTWVzc2FnZVRvKCdtYWluJykoYCR7aWZyYW1lTmFtZX0gdG9nZ2xlZGAsIHsgdmlzaWJsZTogdHJ1ZSB9KTtcbn1cblxuZnVuY3Rpb24gaGlkZUlmcmFtZShpZnJhbWVOYW1lKSB7XG4gIHNlbmRNZXNzYWdlKHsgY29tbWFuZDogJ2hpZGUnLCBuYW1lOiBpZnJhbWVOYW1lIH0pO1xuICBzZW5kTWVzc2FnZVRvKCdtYWluJykoYCR7aWZyYW1lTmFtZX0gdG9nZ2xlZGAsIHsgdmlzaWJsZTogZmFsc2UgfSk7XG59XG5cbmZ1bmN0aW9uIGZvY3VzSWZyYW1lKGlmcmFtZU5hbWUpIHtcbiAgc2VuZE1lc3NhZ2UoeyBjb21tYW5kOiAnZm9jdXMnLCBuYW1lOiBpZnJhbWVOYW1lIH0pO1xufVxuXG5mdW5jdGlvbiBzZW5kTG9hZGVkTWVzc2FnZSgpIHtcbiAgc2VuZE1lc3NhZ2UoeyBjb21tYW5kOiAnbG9hZGVkJyB9KTtcbn1cblxuZnVuY3Rpb24gaXNMb2FkZWRNZXNzYWdlKG1lc3NhZ2UpIHtcbiAgcmV0dXJuIG1lc3NhZ2UgPT09ICdsb2FkZWQnO1xufVxuXG4vKipcbiAqIFNlbmQgZGF0YSBvYnRhaW5lZCBmcm9tIGFuIEFQSSBjYWxsIHRvIGEgcG9wdXAgaWZyYW1lLlxuICovXG5mdW5jdGlvbiBzZW5kQVBJRGF0YU1lc3NhZ2UoZGF0YSkge1xuICBzZW5kTWVzc2FnZVRvKCdwb3B1cCcpKCdBUEkgZGF0YScsIGRhdGEpO1xufVxuXG4vKipcbiAqIFRlc3QgaWYgdHdvIG1lc3NhZ2VzIGFyZSBvZiB0aGUgc2FtZSB0eXBlLlxuICpcbiAqIElnbm9yZXMgYW55IGFkZGl0aW9uYWwgZGF0YSBjb250YWluZWQgd2l0aGluIHRoZSBtZXNzYWdlLlxuICovXG5mdW5jdGlvbiBhcmVNYXRjaGluZ01lc3NhZ2VzKG1lc3NhZ2UsIG90aGVyTWVzc2FnZSkge1xuICByZXR1cm4gWydtZXNzYWdlJywgJ2NvbW1hbmQnLCAnbmFtZSddLmV2ZXJ5KFxuICAgIChrZXkpID0+IG1lc3NhZ2Vba2V5XSAmJiBvdGhlck1lc3NhZ2Vba2V5XSAmJiBtZXNzYWdlW2tleV0gPT09IG90aGVyTWVzc2FnZVtrZXldXG4gICk7XG59XG5cbmZ1bmN0aW9uIGlzQVBJRGF0YU1lc3NhZ2UobWVzc2FnZSkge1xuICByZXR1cm4gYXJlTWF0Y2hpbmdNZXNzYWdlcyhtZXNzYWdlLCB7XG4gICAgY29tbWFuZDogJ21lc3NhZ2UnLFxuICAgIG5hbWU6ICdwb3B1cCcsXG4gICAgbWVzc2FnZTogJ0FQSSBkYXRhJyxcbiAgfSk7XG59XG5cbmZ1bmN0aW9uIGlzUG9wdXBUb2dnbGVNZXNzYWdlKG1lc3NhZ2UpIHtcbiAgcmV0dXJuIGFyZU1hdGNoaW5nTWVzc2FnZXMobWVzc2FnZSwge1xuICAgIGNvbW1hbmQ6ICdtZXNzYWdlJyxcbiAgICBuYW1lOiAnbWFpbicsXG4gICAgbWVzc2FnZTogJ3BvcHVwIHRvZ2dsZWQnLFxuICB9KTtcbn1cblxuZnVuY3Rpb24gYWRkQ2FsbGJhY2tGdW5jdGlvbihmdW5jKSB7XG4gIGxpc3RlbmVyQ2FsbGJhY2tzLnB1c2goZnVuYyk7XG59XG5cbmZ1bmN0aW9uIGhpZGVNYWluSWZyYW1lKCkge1xuICBoaWRlSWZyYW1lKCdtYWluJyk7XG59XG5cbmZ1bmN0aW9uIHNob3dQb3B1cElmcmFtZSgpIHtcbiAgc2hvd0lmcmFtZSgncG9wdXAnKTtcbn1cblxuZnVuY3Rpb24gaGlkZVBvcHVwSWZyYW1lKCkge1xuICBoaWRlSWZyYW1lKCdwb3B1cCcpO1xufVxuXG5mdW5jdGlvbiBmb2N1c1BvcHVwSWZyYW1lKCkge1xuICBmb2N1c0lmcmFtZSgncG9wdXAnKTtcbn1cblxuZnVuY3Rpb24gc2hvd01vZGFsSWZyYW1lKCkge1xuICBzaG93SWZyYW1lKCdtb2RhbCcpO1xufVxuXG5mdW5jdGlvbiBoaWRlTW9kYWxJZnJhbWUoKSB7XG4gIGhpZGVJZnJhbWUoJ21vZGFsJyk7XG59XG5cbmZ1bmN0aW9uIGZvY3VzTW9kYWxJZnJhbWUoKSB7XG4gIGZvY3VzSWZyYW1lKCdtb2RhbCcpO1xufVxuXG5jb25zdCBzZW5kUGluZyA9ICgpID0+IHNlbmRNZXNzYWdlKHsgY29tbWFuZDogJ3BpbmcnIH0pO1xuXG5jb25zdCBvblBvbmcgPSAoY2IpID0+IHtcbiAgY29uc3QgcG9uZyA9IChldmVudCkgPT4ge1xuICAgIGlmIChldmVudC5kYXRhLmNvbW1hbmQgPT09ICdwb25nJykge1xuICAgICAgLy8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lIGNhbGxiYWNrLXJldHVyblxuICAgICAgY2IoZXZlbnQpO1xuICAgIH1cbiAgfTtcbiAgYWRkQ2FsbGJhY2tGdW5jdGlvbihwb25nKTtcbn07XG5cbmZ1bmN0aW9uIHJlc2l6ZUhlaWdodChvcHRpb25hbEhlaWdodCwgb3B0aW9uYWxJZnJhbWVOYW1lKSB7XG4gIGNvbnN0IGJvZHkgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZSgnYm9keScpWzBdO1xuICBzZW5kTWVzc2FnZSh7XG4gICAgY29tbWFuZDogJ3Jlc2l6ZS1oZWlnaHQnLFxuICAgIG5hbWU6IG9wdGlvbmFsSWZyYW1lTmFtZSxcbiAgICBoZWlnaHQ6IG9wdGlvbmFsSGVpZ2h0IHx8IGJvZHkub2Zmc2V0SGVpZ2h0LFxuICB9KTtcbn1cblxuZnVuY3Rpb24gc2Nyb2xsVG9UcnVzdEJveCh0YXJnZXRzKSB7XG4gIHNlbmRNZXNzYWdlKHtcbiAgICBjb21tYW5kOiAnc2Nyb2xsVG8nLFxuICAgIHRhcmdldHMsXG4gIH0pO1xufVxuXG5hZGRFdmVudExpc3RlbmVyKHdpbmRvdywgJ21lc3NhZ2UnLCBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgaWYgKHR5cGVvZiBldmVudC5kYXRhICE9PSAnc3RyaW5nJykge1xuICAgIHJldHVybjtcbiAgfVxuXG4gIGxldCBlO1xuICB0cnkge1xuICAgIGUgPSB7IGRhdGE6IEpTT04ucGFyc2UoZXZlbnQuZGF0YSkgfTsgLy8gVGhpcyBpcyB0byBtYWtlIGl0IElFOCBjb21wYXRpYmxlXG4gIH0gY2F0Y2ggKGUpIHtcbiAgICByZXR1cm47IC8vIHByb2JhYmx5IG5vdCBmb3IgdXNcbiAgfVxuXG4gIGlmIChlLmRhdGEuY29tbWFuZCA9PT0gJ3NldElkJykge1xuICAgIGlkID0gZS5kYXRhLndpZGdldElkO1xuICAgIHNlbmRRdWV1ZSgpO1xuICB9IGVsc2Uge1xuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgbGlzdGVuZXJDYWxsYmFja3MubGVuZ3RoOyBpKyspIHtcbiAgICAgIGNvbnN0IGNhbGxiYWNrID0gbGlzdGVuZXJDYWxsYmFja3NbaV07XG4gICAgICAvLyBlc2xpbnQtZGlzYWJsZS1uZXh0LWxpbmUgY2FsbGJhY2stcmV0dXJuXG4gICAgICBjYWxsYmFjayhlKTtcbiAgICB9XG4gIH1cbn0pO1xuXG5leHBvcnQge1xuICBzZW5kTWVzc2FnZSBhcyBzZW5kLFxuICBjcmVhdGVQb3B1cElmcmFtZSBhcyBjcmVhdGVQb3B1cCxcbiAgY3JlYXRlTW9kYWxJZnJhbWUgYXMgY3JlYXRlTW9kYWwsXG4gIGhpZGVNYWluSWZyYW1lIGFzIGhpZGVUcnVzdEJveCxcbiAgc2hvd1BvcHVwSWZyYW1lIGFzIHNob3dQb3B1cCxcbiAgaGlkZVBvcHVwSWZyYW1lIGFzIGhpZGVQb3B1cCxcbiAgZm9jdXNQb3B1cElmcmFtZSBhcyBmb2N1c1BvcHVwLFxuICBzaG93TW9kYWxJZnJhbWUgYXMgc2hvd01vZGFsLFxuICBoaWRlTW9kYWxJZnJhbWUgYXMgaGlkZU1vZGFsLFxuICBmb2N1c01vZGFsSWZyYW1lIGFzIGZvY3VzTW9kYWwsXG4gIHNlbmRMb2FkZWRNZXNzYWdlIGFzIGxvYWRlZCxcbiAgc2V0U3R5bGVzLFxuICByZXNpemVIZWlnaHQsXG4gIGFkZENhbGxiYWNrRnVuY3Rpb24gYXMgc2V0TGlzdGVuZXIsXG4gIGlzTG9hZGVkTWVzc2FnZSxcbiAgc2VuZEFQSURhdGFNZXNzYWdlLFxuICBpc0FQSURhdGFNZXNzYWdlLFxuICBpc1BvcHVwVG9nZ2xlTWVzc2FnZSxcbiAgc2VuZFBpbmcgYXMgcGluZyxcbiAgb25Qb25nLFxuICBzY3JvbGxUb1RydXN0Qm94LFxufTtcbiIsImV4cG9ydCBjb25zdCBzdHlsZUFsaWdubWVudFBvc2l0aW9ucyA9IFsnbGVmdCcsICdyaWdodCddO1xuIiwiaW1wb3J0IHsgYXBpQ2FsbCB9IGZyb20gJy4uLy4uL2FwaS9jYWxsJztcbmltcG9ydCB7IGdldE9uUGFnZVJlYWR5LCBzaG93VHJ1c3RCb3ggfSBmcm9tICcuLi8uLi91dGlscyc7XG5pbXBvcnQgeyB3aXRoTG9hZGVyIH0gZnJvbSAnLi4vdGVtcGxhdGVzL2xvYWRlcic7XG5pbXBvcnQgeyBlcnJvckZhbGxiYWNrLCByZW1vdmVFcnJvckZhbGxiYWNrIH0gZnJvbSAnLi4vdGVtcGxhdGVzL2Vycm9yRmFsbGJhY2snO1xuaW1wb3J0IHsgc2V0TGlzdGVuZXIsIGlzTG9hZGVkTWVzc2FnZSwgc2VuZEFQSURhdGFNZXNzYWdlIH0gZnJvbSAnLi4vLi4vY29tbXVuaWNhdGlvbic7XG5pbXBvcnQgeyBtYXBPYmplY3QsIHByb21pc2VBbGxPYmplY3QsIHJlamVjdE51bGxhcnlWYWx1ZXMgfSBmcm9tICcuLi8uLi9mbic7XG5cbi8qKlxuICogRGVmaW5lIGEgdW5pcXVlIHNpbmdsZSBmZXRjaCBvYmplY3Qga2V5LCBhbGxvd2luZyB1cyB0byBmbGF0dGVuIGJhY2sgdG8gYVxuICogc2luZ2xlIHNldCBvZiBiYXNlIGRhdGEuIFRoaXMgaXMgYXJiaXRyYXJ5LCBhbmQgaGFzIGJlZW4gc2VsZWN0ZWQgdG8gZW5zdXJlXG4gKiBpdCB3aWxsIG5vdCBiZSBhY2NpZGVudGFsbHkgdXNlZCBpbiBhIGZldGNoUGFyYW1zT2JqZWN0LlxuICovXG5jb25zdCBzaW5nbGVGZXRjaE9iamVjdEtleSA9ICdkZWZhdWx0X3NpbmdsZUZldGNoX2Y5OGFjNzdiJztcblxuLyoqXG4gKiBGbGF0dGVuIGEgZmV0Y2hQYXJhbXNPYmplY3QgdmFsdWUgdG8gb25lIHNpbmdsZSBzZXQgb2YgZmV0Y2hQYXJhbXMsIHdoZXJlXG4gKiB0aGF0IG9iamVjdCBjb250YWlucyBvbmx5IG9uZSB2YWx1ZSwgYW5kIGl0IGlzIGluZGV4ZWQgYnlcbiAqIHNpbmdsZUZldGNoT2JqZWN0S2V5LlxuICovXG5jb25zdCBmbGF0dGVuU2luZ2xlUGFyYW1zID0gKGZldGNoUGFyYW1zT2JqZWN0KSA9PiB7XG4gIGNvbnN0IGtleXMgPSBPYmplY3Qua2V5cyhmZXRjaFBhcmFtc09iamVjdCk7XG4gIHJldHVybiBzaW5nbGVGZXRjaE9iamVjdEtleSBpbiBmZXRjaFBhcmFtc09iamVjdCAmJiBrZXlzLmxlbmd0aCA9PT0gMVxuICAgID8gZmV0Y2hQYXJhbXNPYmplY3Rbc2luZ2xlRmV0Y2hPYmplY3RLZXldXG4gICAgOiBmZXRjaFBhcmFtc09iamVjdDtcbn07XG5cbi8qKlxuICogQ2hlY2sgaWYgYnVzaW5lc3MgaGFzIHNlcnZpY2UgcmV2aWV3c1xuICovXG5jb25zdCBoYXNTZXJ2aWNlUmV2aWV3cyA9ICh7XG4gIGJ1c2luZXNzRW50aXR5OiB7XG4gICAgbnVtYmVyT2ZSZXZpZXdzOiB7IHRvdGFsIH0sXG4gIH0sXG59KSA9PiB0b3RhbCA+IDA7XG5cbi8qKlxuICogQ2hlY2sgaWYgYSBidXNpbmVzcyBoYXMgc2VydmljZSByZXZpZXdzIHVzaW5nIG11bHRpLWZldGNoLlxuICpcbiAqIFRoaXMgY2hlY2tzIHRoYXQgYW55IG9mIHRoZSBiYXNlIGRhdGEgc2V0cyBoYXMgc2VydmljZSByZXZpZXdzIHByZXNlbnRcbiAqIHdpdGhpbiBpdC5cbiAqL1xuY29uc3QgaGFzU2VydmljZVJldmlld3NNdWx0aUZldGNoID0gKGJhc2VEYXRhKSA9PiB7XG4gIGNvbnN0IGtleXMgPSBPYmplY3Qua2V5cyhiYXNlRGF0YSk7XG4gIHJldHVybiBrZXlzLnNvbWUoKGspID0+IGhhc1NlcnZpY2VSZXZpZXdzKGJhc2VEYXRhW2tdKSk7XG59O1xuXG4vKipcbiAqIENoZWNrIGlmIGJ1c2luZXNzIGhhcyBpbXBvcnRlZCBvciByZWd1bGFyIHByb2R1Y3QgcmV2aWV3c1xuICovXG5jb25zdCBoYXNQcm9kdWN0UmV2aWV3cyA9ICh7IHByb2R1Y3RSZXZpZXdzU3VtbWFyeSwgaW1wb3J0ZWRQcm9kdWN0UmV2aWV3c1N1bW1hcnkgfSkgPT4ge1xuICBjb25zdCB0b3RhbFByb2R1Y3RSZXZpZXdzID0gcHJvZHVjdFJldmlld3NTdW1tYXJ5XG4gICAgPyBwcm9kdWN0UmV2aWV3c1N1bW1hcnkubnVtYmVyT2ZSZXZpZXdzLnRvdGFsXG4gICAgOiAwO1xuICBjb25zdCB0b3RhbEltcG9ydGVkUHJvZHVjdFJldmlld3MgPSBpbXBvcnRlZFByb2R1Y3RSZXZpZXdzU3VtbWFyeVxuICAgID8gaW1wb3J0ZWRQcm9kdWN0UmV2aWV3c1N1bW1hcnkubnVtYmVyT2ZSZXZpZXdzLnRvdGFsXG4gICAgOiAwO1xuXG4gIHJldHVybiB0b3RhbFByb2R1Y3RSZXZpZXdzICsgdG90YWxJbXBvcnRlZFByb2R1Y3RSZXZpZXdzID4gMDtcbn07XG5cbi8vIENvbnN0cnVjdCBhIGJhc2UgZGF0YSBjYWxsIHByb21pc2UuXG5jb25zdCBiYXNlRGF0YUNhbGwgPVxuICAodXJpKSA9PlxuICAoeyBidXNpbmVzc1VuaXRJZCwgbG9jYWxlLCAuLi5vcHRzIH0pID0+IHtcbiAgICBjb25zdCBiYXNlRGF0YVBhcmFtcyA9IHJlamVjdE51bGxhcnlWYWx1ZXMoe1xuICAgICAgYnVzaW5lc3NVbml0SWQsXG4gICAgICBsb2NhbGUsXG4gICAgICAuLi5vcHRzLFxuICAgICAgdGhlbWU6IG51bGwsIC8vIEZvcmNlIHJlamVjdGlvbiBvZiB0aGUgdGhlbWUgcGFyYW1cbiAgICB9KTtcbiAgICByZXR1cm4gYXBpQ2FsbCh1cmksIGJhc2VEYXRhUGFyYW1zKTtcbiAgfTtcblxuLyoqXG4gKiBDYWxsIGEgY29uc3RydWN0VHJ1c3RCb3ggY2FsbGJhY2ssIGFuZCB0aGVuIGNvbXBsZXRlIHRoZSBsb2FkaW5nIHByb2Nlc3NcbiAqIGZvciB0aGUgVHJ1c3RCb3guXG4gKi9cbmNvbnN0IGNvbnN0cnVjdFRydXN0Qm94QW5kQ29tcGxldGUgPVxuICAoY29uc3RydWN0VHJ1c3RCb3gsIHBhc3NUb1BvcHVwID0gZmFsc2UsIGhhc1Jldmlld3NGcm9tQmFzZURhdGEgPSBoYXNTZXJ2aWNlUmV2aWV3cykgPT5cbiAgKHsgYmFzZURhdGEsIGxvY2FsZSwgdGhlbWUsIGhhc01vcmVSZXZpZXdzLCBsb2FkTW9yZVJldmlld3MgfSkgPT4ge1xuICAgIGNvbnN0IGhhc1Jldmlld3MgPSBoYXNSZXZpZXdzRnJvbUJhc2VEYXRhKGJhc2VEYXRhKTtcblxuICAgIGNvbnN0cnVjdFRydXN0Qm94KHtcbiAgICAgIGJhc2VEYXRhLFxuICAgICAgbG9jYWxlLFxuICAgICAgaGFzTW9yZVJldmlld3MsXG4gICAgICBsb2FkTW9yZVJldmlld3MsXG4gICAgfSk7XG5cbiAgICAvLyBDb25kaXRpb25hbGx5IHNlbmQgdG8gcG9wdXBcbiAgICBjb25zdCBzZW5kT25Qb3B1cExvYWQgPSAoeyBkYXRhOiBldmVudCB9KSA9PiB7XG4gICAgICBpZiAoaXNMb2FkZWRNZXNzYWdlKGV2ZW50KSkge1xuICAgICAgICBzZW5kQVBJRGF0YU1lc3NhZ2Uoe1xuICAgICAgICAgIGJhc2VEYXRhLFxuICAgICAgICAgIGxvY2FsZSxcbiAgICAgICAgfSk7XG4gICAgICB9XG4gICAgfTtcbiAgICBpZiAocGFzc1RvUG9wdXApIHtcbiAgICAgIHNldExpc3RlbmVyKHNlbmRPblBvcHVwTG9hZCk7XG4gICAgfVxuXG4gICAgc2hvd1RydXN0Qm94KHRoZW1lLCBoYXNSZXZpZXdzKTtcbiAgICByZW1vdmVFcnJvckZhbGxiYWNrKCk7XG4gIH07XG5cbi8qKlxuICogRmV0Y2ggZGF0YSBmcm9tIHRoZSBkYXRhIEFQSSwgbWFraW5nIHplcm8gb3IgbW9yZSByZXF1ZXN0cy5cbiAqXG4gKiBUaGlzIGZ1bmN0aW9uIGFjY2VwdHMgYW4gb2JqZWN0IHdpdGggYXJiaXRyYXJ5IGtleXMsIGFuZCB2YWx1ZXMgd2hpY2ggYXJlXG4gKiBlYWNoIGFuIG9iamVjdCBjb250YWluaW5nIHF1ZXJ5IHBhcmFtcyBmb3Igb25lIHJlcXVlc3QuIEEgcmVxdWVzdCBpcyBtYWRlXG4gKiBmb3IgZWFjaCBxdWVyeSBwYXJhbSBvYmplY3QsIGFuZCB0aGUgcmVzdWx0IGlzIHdyYXBwZWQgd2l0aGluIGFuIG9iamVjdFxuICogaW5kZXhlZCBieSB0aGUga2V5cyBvZiB0aGUgb3JpZ2luYWwgYXJndW1lbnQgb2JqZWN0LlxuICpcbiAqIFRoZXNlIGRhdGEsIHRvZ2V0aGVyIHdpdGggbG9jYWxlIGRhdGEsIGFyZSBwYXNzZWQgdG8gdGhlXG4gKiBjb25zdHJ1Y3RUcnVzdEJveCBjYWxsYmFjay5cbiAqXG4gKiBBbiBvcHRpb25hbCBhcmd1bWVudCwgcGFzc1RvUG9wdXAsIGNhbiBiZSBwcm92aWRlZCB0byB0aGlzIGZ1bmN0aW9uLiBJZiBzZXRcbiAqIHRvIGEgdHJ1dGh5IHZhbHVlLCB0aGlzIGZ1bmN0aW9uIHdpbGwgYXR0ZW1wdCB0byBwYXNzIHRoZSBkYXRhIG9idGFpbmVkIHRvXG4gKiBhbnkgcG9wdXAgaWZyYW1lLlxuICovXG5jb25zdCBtdWx0aUZldGNoRGF0YSA9XG4gICh1cmkpID0+IChmZXRjaFBhcmFtc09iamVjdCwgY29uc3RydWN0VHJ1c3RCb3gsIHBhc3NUb1BvcHVwLCBoYXNSZXZpZXdzRnJvbUJhc2VEYXRhKSA9PiB7XG4gICAgY29uc3QgZmlyc3RGZXRjaFBhcmFtcyA9IGZldGNoUGFyYW1zT2JqZWN0W09iamVjdC5rZXlzKGZldGNoUGFyYW1zT2JqZWN0KVswXV07XG4gICAgY29uc3QgeyBsb2NhbGUsIHRoZW1lID0gJ2xpZ2h0JyB9ID0gZmlyc3RGZXRjaFBhcmFtcztcblxuICAgIGNvbnN0IGJhc2VEYXRhUHJvbWlzZXMgPSBwcm9taXNlQWxsT2JqZWN0KG1hcE9iamVjdChiYXNlRGF0YUNhbGwodXJpKSwgZmV0Y2hQYXJhbXNPYmplY3QpKTtcbiAgICBjb25zdCByZWFkeVByb21pc2UgPSBnZXRPblBhZ2VSZWFkeSgpO1xuXG4gICAgLy8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lIGNvbXBhdC9jb21wYXRcbiAgICBjb25zdCBmZXRjaFByb21pc2UgPSBQcm9taXNlLmFsbChbYmFzZURhdGFQcm9taXNlcywgcmVhZHlQcm9taXNlXSlcbiAgICAgIC50aGVuKChbb3JpZ2luYWxCYXNlRGF0YV0pID0+IHtcbiAgICAgICAgY29uc3QgYmFzZURhdGEgPSBmbGF0dGVuU2luZ2xlUGFyYW1zKG9yaWdpbmFsQmFzZURhdGEpO1xuXG4gICAgICAgIHJldHVybiB7XG4gICAgICAgICAgYmFzZURhdGEsXG4gICAgICAgICAgbG9jYWxlLFxuICAgICAgICAgIHRoZW1lLFxuICAgICAgICB9O1xuICAgICAgfSlcbiAgICAgIC50aGVuKGNvbnN0cnVjdFRydXN0Qm94QW5kQ29tcGxldGUoY29uc3RydWN0VHJ1c3RCb3gsIHBhc3NUb1BvcHVwLCBoYXNSZXZpZXdzRnJvbUJhc2VEYXRhKSlcbiAgICAgIC5jYXRjaCgoZSkgPT4ge1xuICAgICAgICBpZiAoZSAmJiBlLkZhbGxiYWNrTG9nbykge1xuICAgICAgICAgIC8vIHJlbmRlciBmYWxsYmFjayBvbmx5IGlmIGFsbG93ZWQsIGJhc2VkIG9uIHRoZSByZXNwb25zZVxuICAgICAgICAgIHJldHVybiBlcnJvckZhbGxiYWNrKCk7XG4gICAgICAgIH1cbiAgICAgICAgLy8gZG8gbm90aGluZ1xuICAgICAgfSk7XG5cbiAgICB3aXRoTG9hZGVyKGZldGNoUHJvbWlzZSk7XG4gIH07XG5cbi8vIEZldGNoIGFuZCBzdHJ1Y3R1cmUgQVBJIGRhdGEuXG5jb25zdCBmZXRjaERhdGEgPVxuICAodXJpKSA9PiAoZmV0Y2hQYXJhbXMsIGNvbnN0cnVjdFRydXN0Qm94LCBwYXNzVG9Qb3B1cCwgaGFzUmV2aWV3c0Zyb21CYXNlRGF0YSkgPT4ge1xuICAgIGNvbnN0IGZldGNoUGFyYW1zT2JqZWN0ID0geyBbc2luZ2xlRmV0Y2hPYmplY3RLZXldOiBmZXRjaFBhcmFtcyB9O1xuICAgIG11bHRpRmV0Y2hEYXRhKHVyaSkoZmV0Y2hQYXJhbXNPYmplY3QsIGNvbnN0cnVjdFRydXN0Qm94LCBwYXNzVG9Qb3B1cCwgaGFzUmV2aWV3c0Zyb21CYXNlRGF0YSk7XG4gIH07XG5cbmV4cG9ydCB7XG4gIGZldGNoRGF0YSxcbiAgbXVsdGlGZXRjaERhdGEsXG4gIGNvbnN0cnVjdFRydXN0Qm94QW5kQ29tcGxldGUsXG4gIGhhc1NlcnZpY2VSZXZpZXdzLFxuICBoYXNTZXJ2aWNlUmV2aWV3c011bHRpRmV0Y2gsXG4gIGhhc1Byb2R1Y3RSZXZpZXdzLFxufTtcbiIsImltcG9ydCB7IHBvcHVsYXRlRWxlbWVudHMgfSBmcm9tICcuLi8uLi9kb20nO1xuaW1wb3J0IHsgbWtFbGVtV2l0aFN2ZywgYSB9IGZyb20gJy4uL3RlbXBsYXRpbmcnO1xuaW1wb3J0IHsgcmVtb3ZlRWxlbWVudCB9IGZyb20gJy4uLy4uL3V0aWxzJztcbmltcG9ydCB7IGxvZ28gfSBmcm9tICcuLi9hc3NldHMvbG9nbyc7XG5cbmNvbnN0IGVycm9yRmFsbGJhY2sgPSAoY29udGFpbmVyRWxlbWVudCA9ICd0cC13aWRnZXQtZmFsbGJhY2snKSA9PiB7XG4gIGNvbnN0IGNvbnRhaW5lciA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGNvbnRhaW5lckVsZW1lbnQpO1xuXG4gIHBvcHVsYXRlRWxlbWVudHMoW1xuICAgIHtcbiAgICAgIGVsZW1lbnQ6IGNvbnRhaW5lcixcbiAgICAgIHN0cmluZzogYShcbiAgICAgICAge1xuICAgICAgICAgIGhyZWY6ICdodHRwczovL3d3dy50cnVzdHBpbG90LmNvbT91dG1fbWVkaXVtPXRydXN0Ym94ZmFsbGJhY2snLFxuICAgICAgICAgIHRhcmdldDogJ19ibGFuaycsXG4gICAgICAgICAgcmVsOiAnbm9vcGVuZXIgbm9yZWZlcnJlcicsXG4gICAgICAgIH0sXG4gICAgICAgIG1rRWxlbVdpdGhTdmcobG9nbywgJ2ZhbGxiYWNrLWxvZ28nKVxuICAgICAgKSxcbiAgICB9LFxuICBdKTtcbn07XG5cbmNvbnN0IHJlbW92ZUVycm9yRmFsbGJhY2sgPSAoY29udGFpbmVyRWxlbWVudCA9ICd0cC13aWRnZXQtZmFsbGJhY2snKSA9PiB7XG4gIGNvbnN0IGNvbnRhaW5lciA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGNvbnRhaW5lckVsZW1lbnQpO1xuICByZW1vdmVFbGVtZW50KGNvbnRhaW5lcik7XG59O1xuXG5leHBvcnQgeyBlcnJvckZhbGxiYWNrLCByZW1vdmVFcnJvckZhbGxiYWNrIH07XG4iLCJpbXBvcnQgeyBhZGRDbGFzcywgcG9wdWxhdGVFbGVtZW50cyB9IGZyb20gJy4uLy4uL2RvbSc7XG5pbXBvcnQgeyByZW1vdmVFbGVtZW50IH0gZnJvbSAnLi4vLi4vdXRpbHMnO1xuaW1wb3J0IHsgbWtFbGVtV2l0aFN2ZyB9IGZyb20gJy4uL3RlbXBsYXRpbmcnO1xuaW1wb3J0IHsgbG9nbyB9IGZyb20gJy4uL2Fzc2V0cy9sb2dvJztcblxuY29uc3QgZGVmYXVsdExvYWRlckNvbnRhaW5lciA9ICd0cC13aWRnZXQtbG9hZGVyJztcblxuY29uc3QgYWRkTG9hZGVyID0gKGxvYWRlckVsZW1lbnQpID0+IHtcbiAgY29uc3QgbG9hZGVyID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobG9hZGVyRWxlbWVudCk7XG5cbiAgcG9wdWxhdGVFbGVtZW50cyhbXG4gICAge1xuICAgICAgZWxlbWVudDogbG9hZGVyLFxuICAgICAgc3RyaW5nOiBta0VsZW1XaXRoU3ZnKGxvZ28pLFxuICAgIH0sXG4gIF0pO1xufTtcblxuY29uc3QgcmVtb3ZlTG9hZGVyID0gKGxvYWRlckVsZW1lbnQpID0+IHtcbiAgY29uc3QgbG9hZGVyID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobG9hZGVyRWxlbWVudCk7XG4gIGNvbnN0IGxvYWRlckxvYWRlZENsYXNzID0gYCR7bG9hZGVyRWxlbWVudH0tLWxvYWRlZGA7XG4gIGFkZENsYXNzKGxvYWRlciwgbG9hZGVyTG9hZGVkQ2xhc3MpO1xuXG4gIC8vIFJlbW92ZSBsb2FkZXIgYWZ0ZXIgY29tcGxldGlvbiBvZiBhbmltYXRpb24uXG4gIGlmIChsb2FkZXIpIHtcbiAgICBsb2FkZXIuYWRkRXZlbnRMaXN0ZW5lcignYW5pbWF0aW9uZW5kJywgKCkgPT4gcmVtb3ZlRWxlbWVudChsb2FkZXIpKTtcbiAgICBsb2FkZXIuYWRkRXZlbnRMaXN0ZW5lcignd2Via2l0QW5pbWF0aW9uRW5kJywgKCkgPT4gcmVtb3ZlRWxlbWVudChsb2FkZXIpKTtcbiAgICBsb2FkZXIuYWRkRXZlbnRMaXN0ZW5lcignb2FuaW1hdGlvbmVuZCcsICgpID0+IHJlbW92ZUVsZW1lbnQobG9hZGVyKSk7XG4gIH1cbn07XG5cbi8vIENyZWF0ZXMgYSBsb2FkZXIgZWxlbWVudCBpbiB0aGUgRE9NLCB0aGVuIHJlc29sdmVzIGEgcGFzc2VkIHByb21pc2UgYW5kIHJlbW92ZXNcbi8vIHRoZSBsb2FkZXIgb25jZSBjb21wbGV0ZS4gVGhlIGxvYWRlciBpcyBkaXNwbGF5ZWQgb25seSBhZnRlciB0aGUgcGFzc2VkIGRlbGF5XG4vLyBoYXMgZWxhcHNlZC5cbmNvbnN0IHdpdGhMb2FkZXIgPSAocHJvbWlzZSwgeyBsb2FkZXJFbGVtZW50ID0gZGVmYXVsdExvYWRlckNvbnRhaW5lciwgZGVsYXkgPSAxMDAwIH0gPSB7fSkgPT4ge1xuICBjb25zdCBsb2FkZXJUaW1lb3V0SWQgPSBzZXRUaW1lb3V0KCgpID0+IGFkZExvYWRlcihsb2FkZXJFbGVtZW50KSwgZGVsYXkpO1xuICByZXR1cm4gcHJvbWlzZS5maW5hbGx5KCgpID0+IHtcbiAgICBjbGVhclRpbWVvdXQobG9hZGVyVGltZW91dElkKTtcbiAgICByZW1vdmVMb2FkZXIobG9hZGVyRWxlbWVudCk7XG4gIH0pO1xufTtcblxuZXhwb3J0IHsgd2l0aExvYWRlciB9O1xuIiwiaW1wb3J0IHsgZmV0Y2hEYXRhLCBoYXNQcm9kdWN0UmV2aWV3cyB9IGZyb20gJy4vZmV0Y2hEYXRhJztcbmltcG9ydCB7IGFwaUNhbGwgfSBmcm9tICcuLi8uLi9hcGkvY2FsbCc7XG5pbXBvcnQgUmV2aWV3RmV0Y2hlciBmcm9tICcuLi8uLi9hcGkvcmV2aWV3RmV0Y2hlcic7XG5cbi8qKlxuICogRmV0Y2hlcyBkYXRhIGZvciBhIHByb2R1Y3QgYXR0cmlidXRlIFRydXN0Qm94LlxuICpcbiAqIFRoaXMgdXNlcyBhIFwibmV3LXN0eWxlXCIgZW5kcG9pbnQsIHdoaWNoIHRha2VzIGEgdGVtcGxhdGVJZCBhbmQgc3VwcGxpZXMgZGF0YVxuICogYmFzZWQgb24gdGhhdC5cbiAqL1xuY29uc3QgZmV0Y2hQcm9kdWN0RGF0YSA9XG4gICh0ZW1wbGF0ZUlkKSA9PlxuICAoZmV0Y2hQYXJhbXMsIGNvbnN0cnVjdFRydXN0Qm94LCBwYXNzVG9Qb3B1cCA9IGZhbHNlLCBpbmNsdWRlSW1wb3J0ZWRSZXZpZXdzID0gZmFsc2UpID0+IHtcbiAgICAvLyBBZGQgZXh0cmEgZGF0YSB0byB0aGUgY29uc3RydWN0VHJ1c3RCb3ggY2FsbGJhY2ssIHdoZXJlIHdlIGFyZSBmZXRjaGluZyByZXZpZXdzXG4gICAgY29uc3Qgd3JhcHBlZENvbnN0cnVjdCA9ICh7IGJhc2VEYXRhLCBsb2NhbGUsIC4uLmFyZ3MgfSkgPT4ge1xuICAgICAgY29uc3QgZmV0Y2hlciA9IG5ldyBSZXZpZXdGZXRjaGVyKHtcbiAgICAgICAgYmFzZURhdGEsXG4gICAgICAgIGluY2x1ZGVJbXBvcnRlZFJldmlld3MsXG4gICAgICAgIHJldmlld3NQZXJQYWdlOiBwYXJzZUludChmZXRjaFBhcmFtcy5yZXZpZXdzUGVyUGFnZSksXG4gICAgICAgIGxvY2FsZSxcbiAgICAgICAgLi4uYXJncyxcbiAgICAgIH0pO1xuICAgICAgcmV0dXJuIGZldGNoZXIuY29uc3VtZVJldmlld3MoY29uc3RydWN0VHJ1c3RCb3gpKCk7XG4gICAgfTtcblxuICAgIGNvbnN0IGNvbnN0cnVjdCA9IGZldGNoUGFyYW1zLnJldmlld3NQZXJQYWdlID4gMCA/IHdyYXBwZWRDb25zdHJ1Y3QgOiBjb25zdHJ1Y3RUcnVzdEJveDtcbiAgICBmZXRjaERhdGEoYC90cnVzdGJveC1kYXRhLyR7dGVtcGxhdGVJZH1gKShcbiAgICAgIGZldGNoUGFyYW1zLFxuICAgICAgY29uc3RydWN0LFxuICAgICAgcGFzc1RvUG9wdXAsXG4gICAgICBoYXNQcm9kdWN0UmV2aWV3c1xuICAgICk7XG4gIH07XG5cbi8qKlxuICogRmV0Y2hlcyBwcm9kdWN0IHJldmlldyBkYXRhIGdpdmVuIGFuIElEIGFuZCBhIGxvY2FsZS5cbiAqL1xuY29uc3QgZmV0Y2hQcm9kdWN0UmV2aWV3ID0gKHByb2R1Y3RSZXZpZXdJZCwgbG9jYWxlLCBjYWxsYmFjaykgPT4ge1xuICBhcGlDYWxsKGAvcHJvZHVjdC1yZXZpZXdzLyR7cHJvZHVjdFJldmlld0lkfWAsIHsgbG9jYWxlIH0pLnRoZW4oY2FsbGJhY2spO1xufTtcblxuZXhwb3J0IHsgZmV0Y2hQcm9kdWN0RGF0YSwgZmV0Y2hQcm9kdWN0UmV2aWV3IH07XG4iLCJpbXBvcnQgeyBzYW5pdGl6ZUh0bWxQcm9wLCBzYW5pdGl6ZUNvbG9yIH0gZnJvbSAnLi4vLi4vdXRpbHMnO1xuXG4vKlxuICogSUUxMSBkb2VzIG5vdCBwcm9wZXJseSBkaXNwbGF5IFNWRyB0YWdzLCBleGNlcHQgdXNpbmcgb25lIG9mIHNldmVyYWwgaGFja3MuXG4gKiBTbywgd2UgdXNlIG9uZSBiZWxvdzogd2Ugd3JhcCBlYWNoIFNWRyBpbiBhIGRpdiBlbGVtZW50LCB3aXRoIHBhcnRpY3VsYXJcbiAqIHN0eWxpbmcgYXR0YWNoZWQuIFdlIGRvIHRoaXMgZm9sbG93aW5nIE9wdGlvbiA0IGluIHRoZSBhcnRpY2xlIGF0XG4gKiBodHRwczovL2Nzcy10cmlja3MuY29tL3NjYWxlLXN2Zy8uXG4gKi9cblxuY29uc3Qgd3JhcFN2ZyA9IChkaW1lbnNpb25zLCBpbm5lciwgcHJvcHMgPSB7fSkgPT4ge1xuICBjb25zdCBzYW5pdGl6ZWRQcm9wcyA9IE9iamVjdC5rZXlzKHByb3BzKS5yZWR1Y2UoKGFjYywgY3VyKSA9PiB7XG4gICAgYWNjW2N1cl0gPSBzYW5pdGl6ZUh0bWxQcm9wKHByb3BzW2N1cl0pO1xuICAgIGlmIChjdXIgPT09ICdjb2xvcicpIHtcbiAgICAgIGFjY1tjdXJdID0gc2FuaXRpemVDb2xvcihhY2NbY3VyXSk7XG4gICAgfVxuICAgIHJldHVybiBhY2M7XG4gIH0sIHt9KTtcbiAgcmV0dXJuIGBcbiAgICA8ZGl2IHN0eWxlPVwicG9zaXRpb246IHJlbGF0aXZlOyBoZWlnaHQ6IDA7IHdpZHRoOiAxMDAlOyBwYWRkaW5nOiAwOyBwYWRkaW5nLWJvdHRvbTogJHtcbiAgICAgIChkaW1lbnNpb25zLmhlaWdodCAvIGRpbWVuc2lvbnMud2lkdGgpICogMTAwXG4gICAgfSU7XCI+XG4gICAgICAke2lubmVyKGRpbWVuc2lvbnMsIHNhbml0aXplZFByb3BzKX1cbiAgICA8L2Rpdj5cbiAgYDtcbn07XG5cbmNvbnN0IHN2Z1N0YXJTdHlsZSA9ICdzdHlsZT1cInBvc2l0aW9uOiBhYnNvbHV0ZTsgaGVpZ2h0OiAxMDAlOyB3aWR0aDogMTAwJTsgbGVmdDogMDsgdG9wOiAwO1wiJztcblxuY29uc3QgU0NBTEVfRElNRU5TSU9OU184MHgxNSA9ICc4MHgxNSc7XG5jb25zdCBTQ0FMRV9ESU1FTlNJT05TXzkweDE2ID0gJzkweDE2JztcbmNvbnN0IFNDQUxFX0RJTUVOU0lPTlNfMTA1eDE5ID0gJzEwNXgxOSc7XG5cbmV4cG9ydCB7XG4gIHdyYXBTdmcsXG4gIHN2Z1N0YXJTdHlsZSxcbiAgU0NBTEVfRElNRU5TSU9OU184MHgxNSxcbiAgU0NBTEVfRElNRU5TSU9OU185MHgxNixcbiAgU0NBTEVfRElNRU5TSU9OU18xMDV4MTksXG59O1xuIiwiaW1wb3J0IHsgd3JhcFN2Zywgc3ZnU3RhclN0eWxlIH0gZnJvbSAnLi9oZWxwZXJzJztcblxuY29uc3QgaWNvbiA9IChkaW1lbnNpb25zKSA9PiB7XG4gIGNvbnN0IHRpdGxlSWQgPSBgdHJ1c3RwaWxvdExvZ28tJHtNYXRoLnJhbmRvbSgpLnRvU3RyaW5nKDM2KS5zdWJzdHJpbmcoMil9YDtcblxuICByZXR1cm4gYFxuICAgIDxzdmcgcm9sZT1cImltZ1wiIHZpZXdCb3g9XCIwIDAgJHtkaW1lbnNpb25zLndpZHRofSAke2RpbWVuc2lvbnMuaGVpZ2h0fVwiIGFyaWEtbGFiZWxsZWRieT1cIiR7dGl0bGVJZH1cIiAgd2lkdGg9XCIke2RpbWVuc2lvbnMud2lkdGh9XCIgaGVpZ2h0PVwiJHtkaW1lbnNpb25zLmhlaWdodH1cIiB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgJHtzdmdTdGFyU3R5bGV9PlxuICAgICAgPHRpdGxlIGlkPVwiJHt0aXRsZUlkfVwiPlRydXN0cGlsb3Q8L3RpdGxlPlxuICAgICAgPHBhdGggY2xhc3M9XCJ0cC1sb2dvX190ZXh0XCIgZD1cIk0zMy4wNzQ3NzQgMTEuMDcwMDVINDUuODE4MDZ2Mi4zNjQxOTZoLTUuMDEwNjU2djEzLjI5MDMxNmgtMi43NTUzMDZWMTMuNDM0MjQ2aC00Ljk4ODQzNVYxMS4wNzAwNWguMDExMTF6bTEyLjE5ODg5MiA0LjMxOTYyOWgyLjM1NTM0MXYyLjE4NzQzM2guMDQ0NDRjLjA3Nzc3MS0uMzA5MzM0LjIyMjIwMy0uNjA3NjIuNDMzMjk1LS44OTQ4NTkuMjExMDkyLS4yODcyMzkuNDY2NjI0LS41NjM0My43NjY1OTctLjc5NTQzLjI5OTk3Mi0uMjQzMDQ4LjYzMzI3Ni0uNDMwODU4Ljk5OTkwOS0uNTg1NTI1LjM2NjYzMy0uMTQzNjIuNzQ0Mzc3LS4yMjA5NTMgMS4xMjIxMi0uMjIwOTUzLjI4ODg2MyAwIC40OTk5NTUuMDExMDQ3LjYxMTA1Ni4wMjIwOTUuMTExMS4wMTEwNDguMjIyMjAyLjAzMzE0My4zNDQ0MTMuMDQ0MTl2Mi40MDgzODdjLS4xNzc3NjItLjAzMzE0My0uMzU1NTIzLS4wNTUyMzgtLjU0NDM5NS0uMDc3MzMzLS4xODg4NzItLjAyMjA5Ni0uMzY2NjMzLS4wMzMxNDMtLjU0NDM5NS0uMDMzMTQzLS40MjIxODQgMC0uODIyMTQ4LjA4ODM4LTEuMTk5ODkxLjI1NDA5Ni0uMzc3NzQ0LjE2NTcxNC0uNjk5OTM2LjQxOTgxLS45Nzc2ODkuNzQwMTkyLS4yNzc3NTMuMzMxNDI5LS40OTk5NTUuNzI5MTQ0LS42NjY2MDYgMS4yMTUyNC0uMTY2NjUyLjQ4NjA5Ny0uMjQ0NDIyIDEuMDM4NDgtLjI0NDQyMiAxLjY2ODE5NXY1LjM5MTI1aC0yLjUxMDg4M1YxNS4zODk2OGguMDExMTF6bTE4LjIyMDU2NyAxMS4zMzQ4ODNINjEuMDI3Nzl2LTEuNTc5ODEzaC0uMDQ0NDRjLS4zMTEwODMuNTc0NDc3LS43NjY1OTcgMS4wMjc0My0xLjM3NzY1MyAxLjM2OTkwOC0uNjExMDU1LjM0MjQ3Ny0xLjIzMzIyMS41MTkyNC0xLjg2NjQ5Ny41MTkyNC0xLjQ5OTg2NCAwLTIuNTg4NjU0LS4zNjQ1NzMtMy4yNTUyNi0xLjEwNDc2NS0uNjY2NjA2LS43NDAxOTMtLjk5OTkwOS0xLjg1NjAwNS0uOTk5OTA5LTMuMzQ3NDM3VjE1LjM4OTY4aDIuNTEwODgzdjYuOTQ4OTY4YzAgLjk5NDI4OC4xODg4NzIgMS43MDEzMzcuNTc3NzI1IDIuMTEwMS4zNzc3NDQuNDA4NzYzLjkyMjEzOS42MTg2NjggMS42MTA5NjUuNjE4NjY4LjUzMzI4NSAwIC45NjY1OC0uMDc3MzMzIDEuMzIyMTAyLS4yNDMwNDguMzU1NTI0LS4xNjU3MTQuNjQ0Mzg2LS4zNzU2Mi44NTU0NzgtLjY1MTgxLjIyMjIwMi0uMjY1MTQ0LjM3Nzc0NC0uNTk2NTc0LjQ3NzczNS0uOTcyMTk0LjA5OTk5LS4zNzU2Mi4xNDQ0MzEtLjc4NDM4Mi4xNDQ0MzEtMS4yMjYyODh2LTYuNTczMzQ5aDIuNTEwODgzdjExLjMyMzgzNnptNC4yNzczOS0zLjYzNDY3NWMuMDc3NzcuNzI5MTQ0LjM1NTUyMiAxLjIzNzMzNi44MzMyNTcgMS41MzU2MjMuNDg4ODQ0LjI4NzIzOCAxLjA2NjU3LjQ0MTkwNSAxLjc0NDI4Ni40NDE5MDUuMjMzMzEyIDAgLjQ5OTk1NC0uMDIyMDk1Ljc5OTkyNy0uMDU1MjM4LjI5OTk3My0uMDMzMTQzLjU4ODgzNi0uMTEwNDc2Ljg0NDM2OC0uMjA5OTA1LjI2NjY0Mi0uMDk5NDI5LjQ3NzczNC0uMjU0MDk2LjY1NTQ5Ni0uNDUyOTU0LjE2NjY1Mi0uMTk4ODU3LjI0NDQyMi0uNDUyOTUzLjIzMzMxMi0uNzczMzM1LS4wMTExMS0uMzIwMzgxLS4xMzMzMjEtLjU4NTUyNS0uMzU1NTIzLS43ODQzODItLjIyMjIwMi0uMjA5OTA2LS40OTk5NTUtLjM2NDU3My0uODQ0MzY4LS40OTcxNDQtLjM0NDQxMy0uMTIxNTI1LS43MzMyNjctLjIzMi0xLjE3NzY3LS4zMjAzODItLjQ0NDQwNS0uMDg4MzgxLS44ODg4MDktLjE4NzgxLTEuMzQ0MzIzLS4yODcyMzktLjQ2NjYyNC0uMDk5NDI5LS45MjIxMzgtLjIzMi0xLjM1NTQzMi0uMzc1NjItLjQzMzI5NC0uMTQzNjItLjgyMjE0OC0uMzQyNDc3LTEuMTY2NTYxLS41OTY1NzMtLjM0NDQxMy0uMjQzMDQ4LS42MjIxNjYtLjU2MzQzLS44MjIxNDgtLjk1MDA5Ny0uMjExMDkyLS4zODY2NjgtLjMxMTA4My0uODYxNzE2LS4zMTEwODMtMS40MzYxOTQgMC0uNjE4NjY4LjE1NTU0Mi0xLjEyNjg2LjQ1NTUxNS0xLjU0NjY3LjI5OTk3Mi0uNDE5ODEuNjg4ODI2LS43NTEyNCAxLjE0NDM0LTEuMDA1MzM2LjQ2NjYyNC0uMjU0MDk1Ljk3NzY5LS40MzA4NTggMS41NDQzMDQtLjU0MTMzNC41NjY2MTUtLjA5OTQyOSAxLjExMTAxLS4xNTQ2NjcgMS42MjIwNzUtLjE1NDY2Ny41ODg4MzYgMCAxLjE1NTQ1LjA2NjI4NiAxLjY4ODczNi4xODc4MS41MzMyODUuMTIxNTI0IDEuMDIyMTMuMzIwMzgxIDEuNDU1NDIzLjYwNzYyLjQzMzI5NC4yNzYxOTEuNzg4ODE3LjY0MDc2NCAxLjA3NzY4IDEuMDgyNjcuMjg4ODYzLjQ0MTkwNS40NjY2MjQuOTgzMjQuNTQ0Mzk1IDEuNjEyOTU1aC0yLjYyMTk4NGMtLjEyMjIxMS0uNTk2NTcyLS4zODg4NTQtMS4wMDUzMzUtLjgyMjE0OC0xLjIwNDE5My0uNDMzMjk0LS4yMDk5MDUtLjkzMzI0OC0uMzA5MzM0LTEuNDg4NzUzLS4zMDkzMzQtLjE3Nzc2MiAwLS4zODg4NTQuMDExMDQ4LS42MzMyNzYuMDQ0MTktLjI0NDQyMi4wMzMxNDQtLjQ2NjYyNC4wODgzODItLjY4ODgyNi4xNjU3MTUtLjIxMTA5Mi4wNzczMzQtLjM4ODg1NC4xOTg4NTgtLjU0NDM5NS4zNTM1MjUtLjE0NDQzMi4xNTQ2NjctLjIyMjIwMy4zNTM1MjUtLjIyMjIwMy42MDc2MiAwIC4zMDkzMzUuMTExMTAxLjU1MjM4My4zMjIxOTMuNzQwMTkzLjIxMTA5Mi4xODc4MS40ODg4NDUuMzQyNDc3LjgzMzI1OC40NzUwNDguMzQ0NDEzLjEyMTUyNC43MzMyNjcuMjMyIDEuMTc3NjcxLjMyMDM4Mi40NDQ0MDQuMDg4MzgxLjg5OTkxOC4xODc4MSAxLjM2NjU0Mi4yODcyMzkuNDU1NTE1LjA5OTQyOS44OTk5MTkuMjMyIDEuMzQ0MzIzLjM3NTYyLjQ0NDQwNC4xNDM2Mi44MzMyNTcuMzQyNDc3IDEuMTc3NjcuNTk2NTczLjM0NDQxNC4yNTQwOTUuNjIyMTY2LjU2MzQzLjgzMzI1OC45MzkwNS4yMTEwOTIuMzc1NjIuMzIyMTkzLjg1MDY2OC4zMjIxOTMgMS40MDMwNSAwIC42NzM5MDYtLjE1NTU0MSAxLjIzNzMzNi0uNDY2NjI0IDEuNzEyMzg1LS4zMTEwODMuNDY0MDAxLS43MTEwNDcuODUwNjY5LTEuMTk5ODkxIDEuMTM3OTA3LS40ODg4NDUuMjg3MjQtMS4wNDQzNS41MDgxOTItMS42NDQyOTUuNjQwNzY0LS41OTk5NDYuMTMyNTcyLTEuMTk5ODkxLjE5ODg1Ny0xLjc4ODcyNy4xOTg4NTctLjcyMjE1NiAwLTEuMzg4NzYyLS4wNzczMzMtMS45OTk4MTgtLjI0MzA0OC0uNjExMDU2LS4xNjU3MTQtMS4xNDQzNC0uNDA4NzYzLTEuNTg4NzQ1LS43MjkxNDQtLjQ0NDQwNC0uMzMxNDMtLjc5OTkyNy0uNzQwMTkyLTEuMDU1NDYtMS4yMjYyODktLjI1NTUzMi0uNDg2MDk2LS4zODg4NTMtMS4wNzE2MjEtLjQxMTA3My0xLjc0NTUyOGgyLjUzMzEwM3YtLjAyMjA5NXptOC4yODgxMzUtNy43MDAyMDhoMS44OTk4Mjh2LTMuNDAyNjc1aDIuNTEwODgzdjMuNDAyNjc1aDIuMjY2NDZ2MS44NjcwNTJoLTIuMjY2NDZ2Ni4wNTQxMDljMCAuMjY1MTQzLjAxMTExLjQ4NjA5Ni4wMzMzMy42ODQ5NTQuMDIyMjIuMTg3ODEuMDc3NzcuMzUzNTI0LjE1NTU0Mi40ODYwOTYuMDc3NzcuMTMyNTcyLjE5OTk4MS4yMzIuMzY2NjMzLjI5ODI4Ny4xNjY2NTEuMDY2Mjg1LjM3Nzc0My4wOTk0MjguNjY2NjA2LjA5OTQyOC4xNzc3NjIgMCAuMzU1NTIzIDAgLjUzMzI4NS0uMDExMDQ3LjE3Nzc2Mi0uMDExMDQ4LjM1NTUyMy0uMDMzMTQzLjUzMzI4NS0uMDc3MzM0djEuOTMzMzM4Yy0uMjc3NzUzLjAzMzE0My0uNTU1NTA1LjA1NTIzOC0uODExMDM4LjA4ODM4MS0uMjY2NjQyLjAzMzE0My0uNTMzMjg1LjA0NDE5LS44MTEwMzcuMDQ0MTktLjY2NjYwNiAwLTEuMTk5ODkxLS4wNjYyODUtMS41OTk4NTUtLjE4NzgxLS4zOTk5NjMtLjEyMTUyMy0uNzIyMTU2LS4zMDkzMzMtLjk0NDM1OC0uNTUyMzgxLS4yMzMzMTMtLjI0MzA0OS0uMzc3NzQ0LS41NDEzMzUtLjQ2NjYyNS0uOTA1OTA3LS4wNzc3Ny0uMzY0NTczLS4xMzMzMi0uNzg0MzgzLS4xNDQ0MzEtMS4yNDgzODR2LTYuNjgzODI1aC0xLjg5OTgyN3YtMS44ODkxNDdoLS4wMjIyMnptOC40NTQ3ODggMGgyLjM3NzU2MlYxNi45MjUzaC4wNDQ0NGMuMzU1NTIzLS42NjI4NTguODQ0MzY4LTEuMTI2ODYgMS40Nzc2NDQtMS40MTQwOTguNjMzMjc2LS4yODcyMzkgMS4zMTA5OTItLjQzMDg1OCAyLjA1NTM2OS0uNDMwODU4Ljg5OTkxOCAwIDEuNjc3NjI1LjE1NDY2NyAyLjM0NDIzMS40NzUwNDguNjY2NjA2LjMwOTMzNSAxLjIyMjExMS43NDAxOTMgMS42NjY1MTUgMS4yOTI1NzUuNDQ0NDA1LjU1MjM4Mi43NjY1OTcgMS4xOTMxNDUuOTg4OCAxLjkyMjI5LjIyMjIwMi43MjkxNDUuMzMzMzAzIDEuNTEzNTI3LjMzMzMwMyAyLjM0MjEgMCAuNzYyMjg4LS4wOTk5OTEgMS41MDI0OC0uMjk5OTczIDIuMjA5NTMtLjE5OTk4Mi43MTgwOTYtLjQ5OTk1NSAxLjM0NzgxMi0uODk5OTE4IDEuOTAwMTk0LS4zOTk5NjQuNTUyMzgzLS45MTEwMjkuOTgzMjQtMS41MzMxOTQgMS4zMTQ2Ny0uNjIyMTY2LjMzMTQzLTEuMzQ0MzIzLjQ5NzE0NC0yLjE4ODY5LjQ5NzE0NC0uMzY2NjM0IDAtLjczMzI2Ny0uMDMzMTQzLTEuMDk5OS0uMDk5NDI5LS4zNjY2MzQtLjA2NjI4Ni0uNzIyMTU3LS4xNzY3NjItMS4wNTU0Ni0uMzIwMzgxLS4zMzMzMDMtLjE0MzYyLS42NTU0OTYtLjMzMTQzLS45MzMyNDktLjU2MzQzLS4yODg4NjMtLjIzMi0uNTIyMTc1LS40OTcxNDQtLjcyMjE1Ny0uNzk1NDNoLS4wNDQ0NHY1LjY1NjM5M2gtMi41MTA4ODNWMTUuMzg5Njh6bTguNzc2OTggNS42Nzg0OWMwLS41MDgxOTMtLjA2NjY2LTEuMDA1MzM3LS4xOTk5ODEtMS40OTE0MzMtLjEzMzMyMS0uNDg2MDk2LS4zMzMzMDMtLjkwNTkwNy0uNTk5OTQ2LTEuMjgxNTI3LS4yNjY2NDItLjM3NTYyLS41OTk5NDUtLjY3MzkwNi0uOTg4Nzk5LS44OTQ4NTktLjM5OTk2My0uMjIwOTUzLS44NTU0NzgtLjM0MjQ3Ny0xLjM2NjU0Mi0uMzQyNDc3LTEuMDU1NDYgMC0xLjg1NTM4Ny4zNjQ1NzItMi4zODg2NzIgMS4wOTM3MTctLjUzMzI4NS43MjkxNDQtLjc5OTkyOCAxLjcwMTMzNy0uNzk5OTI4IDIuOTE2NTc4IDAgLjU3NDQ3OC4wNjY2NjEgMS4xMDQ3NjQuMjExMDkyIDEuNTkwODYuMTQ0NDMyLjQ4NjA5Ny4zNDQ0MTQuOTA1OTA4LjYzMzI3NiAxLjI1OTQzMi4yNzc3NTMuMzUzNTI1LjYxMTA1Ni42Mjk3MTYuOTk5OTEuODI4NTc0LjM4ODg1My4yMDk5MDUuODQ0MzY3LjMwOTMzNCAxLjM1NTQzMi4zMDkzMzQuNTc3NzI1IDAgMS4wNTU0Ni0uMTIxNTI0IDEuNDU1NDIzLS4zNTM1MjUuMzk5OTY0LS4yMzIuNzIyMTU3LS41NDEzMzUuOTc3NjktLjkwNTkwNy4yNTU1MzEtLjM3NTYyLjQ0NDQwMy0uNzk1NDMuNTU1NTA0LTEuMjcwNDc5LjA5OTk5MS0uNDc1MDQ5LjE1NTU0Mi0uOTYxMTQ1LjE1NTU0Mi0xLjQ1ODI4OXptNC40MzI5MzEtOS45OTgxMmgyLjUxMDg4M3YyLjM2NDE5N2gtMi41MTA4ODNWMTEuMDcwMDV6bTAgNC4zMTk2M2gyLjUxMDg4M3YxMS4zMzQ4ODNoLTIuNTEwODgzVjE1LjM4OTY3OXptNC43NTUxMjQtNC4zMTk2M2gyLjUxMDg4M3YxNS42NTQ1MTNoLTIuNTEwODgzVjExLjA3MDA1em0xMC4yMTAxODQgMTUuOTYzODQ3Yy0uOTExMDI5IDAtMS43MjIwNjYtLjE1NDY2Ny0yLjQzMzExMy0uNDUyOTUzLS43MTEwNDYtLjI5ODI4Ny0xLjMxMDk5Mi0uNzE4MDk3LTEuODEwOTQ2LTEuMjM3MzM3LS40ODg4NDUtLjUzMDI4Ny0uODY2NTg4LTEuMTYwMDAyLTEuMTIyMTItMS44ODkxNDctLjI1NTUzMy0uNzI5MTQ0LS4zODg4NTQtMS41MzU2MjItLjM4ODg1NC0yLjQwODM4NiAwLS44NjE3MTYuMTMzMzIxLTEuNjU3MTQ3LjM4ODg1My0yLjM4NjI5MS4yNTU1MzMtLjcyOTE0NS42MzMyNzYtMS4zNTg4NiAxLjEyMjEyLTEuODg5MTQ4LjQ4ODg0NS0uNTMwMjg3IDEuMDk5OS0uOTM5MDUgMS44MTA5NDctMS4yMzczMzYuNzExMDQ3LS4yOTgyODYgMS41MjIwODQtLjQ1Mjk1MyAyLjQzMzExMy0uNDUyOTUzLjkxMTAyOCAwIDEuNzIyMDY2LjE1NDY2NyAyLjQzMzExMi40NTI5NTMuNzExMDQ3LjI5ODI4NyAxLjMxMDk5Mi43MTgwOTcgMS44MTA5NDcgMS4yMzczMzYuNDg4ODQ0LjUzMDI4Ny44NjY1ODggMS4xNjAwMDMgMS4xMjIxMiAxLjg4OTE0OC4yNTU1MzIuNzI5MTQ0LjM4ODg1NCAxLjUyNDU3NS4zODg4NTQgMi4zODYyOSAwIC44NzI3NjUtLjEzMzMyMiAxLjY3OTI0My0uMzg4ODU0IDIuNDA4Mzg3LS4yNTU1MzIuNzI5MTQ1LS42MzMyNzYgMS4zNTg4Ni0xLjEyMjEyIDEuODg5MTQ3LS40ODg4NDUuNTMwMjg3LTEuMDk5OS45MzkwNS0xLjgxMDk0NyAxLjIzNzMzNy0uNzExMDQ2LjI5ODI4Ni0xLjUyMjA4NC40NTI5NTMtMi40MzMxMTIuNDUyOTUzem0wLTEuOTc3NTI4Yy41NTU1MDUgMCAxLjA0NDM1LS4xMjE1MjQgMS40NTU0MjMtLjM1MzUyNS40MTEwNzQtLjIzMi43NDQzNzctLjU0MTMzNSAxLjAxMTAyLS45MTY5NTQuMjY2NjQyLS4zNzU2Mi40NTU1MTMtLjgwNjQ3OC41ODg4MzUtMS4yODE1MjcuMTIyMjEtLjQ3NTA0OS4xODg4NzItLjk2MTE0NS4xODg4NzItMS40NTgyOSAwLS40ODYwOTYtLjA2NjY2MS0uOTYxMTQ0LS4xODg4NzItMS40NDcyNC0uMTIyMjExLS40ODYwOTctLjMyMjE5My0uOTA1OTA3LS41ODg4MzYtMS4yODE1MjctLjI2NjY0Mi0uMzc1NjItLjU5OTk0NS0uNjczOTA3LTEuMDExMDE5LS45MDU5MDctLjQxMTA3NC0uMjMyLS44OTk5MTgtLjM1MzUyNS0xLjQ1NTQyMy0uMzUzNTI1LS41NTU1MDUgMC0xLjA0NDM1LjEyMTUyNC0xLjQ1NTQyNC4zNTM1MjUtLjQxMTA3My4yMzItLjc0NDM3Ni41NDEzMzQtMS4wMTEwMTkuOTA1OTA3LS4yNjY2NDIuMzc1NjItLjQ1NTUxNC43OTU0My0uNTg4ODM1IDEuMjgxNTI2LS4xMjIyMTEuNDg2MDk3LS4xODg4NzIuOTYxMTQ1LS4xODg4NzIgMS40NDcyNDIgMCAuNDk3MTQ0LjA2NjY2Ljk4MzI0LjE4ODg3MiAxLjQ1ODI4OS4xMjIyMS40NzUwNDkuMzIyMTkzLjkwNTkwNy41ODg4MzUgMS4yODE1MjcuMjY2NjQzLjM3NTYyLjU5OTk0Ni42ODQ5NTQgMS4wMTEwMi45MTY5NTQuNDExMDczLjI0MzA0OC44OTk5MTguMzUzNTI1IDEuNDU1NDIzLjM1MzUyNXptNi40ODgzLTkuNjY2NjloMS44OTk4Mjd2LTMuNDAyNjc0aDIuNTEwODgzdjMuNDAyNjc1aDIuMjY2NDZ2MS44NjcwNTJoLTIuMjY2NDZ2Ni4wNTQxMDljMCAuMjY1MTQzLjAxMTExLjQ4NjA5Ni4wMzMzMy42ODQ5NTQuMDIyMjIuMTg3ODEuMDc3NzcuMzUzNTI0LjE1NTU0MS40ODYwOTYuMDc3NzcxLjEzMjU3Mi4xOTk5ODIuMjMyLjM2NjYzNC4yOTgyODcuMTY2NjUxLjA2NjI4NS4zNzc3NDMuMDk5NDI4LjY2NjYwNi4wOTk0MjguMTc3NzYyIDAgLjM1NTUyMyAwIC41MzMyODUtLjAxMTA0Ny4xNzc3NjItLjAxMTA0OC4zNTU1MjMtLjAzMzE0My41MzMyODUtLjA3NzMzNHYxLjkzMzMzOGMtLjI3Nzc1My4wMzMxNDMtLjU1NTUwNS4wNTUyMzgtLjgxMTAzOC4wODgzODEtLjI2NjY0Mi4wMzMxNDMtLjUzMzI4NS4wNDQxOS0uODExMDM3LjA0NDE5LS42NjY2MDYgMC0xLjE5OTg5MS0uMDY2Mjg1LTEuNTk5ODU1LS4xODc4MS0uMzk5OTYzLS4xMjE1MjMtLjcyMjE1Ni0uMzA5MzMzLS45NDQzNTgtLjU1MjM4MS0uMjMzMzEzLS4yNDMwNDktLjM3Nzc0NC0uNTQxMzM1LS40NjY2MjUtLjkwNTkwNy0uMDc3NzctLjM2NDU3My0uMTMzMzIxLS43ODQzODMtLjE0NDQzMS0xLjI0ODM4NHYtNi42ODM4MjVoLTEuODk5ODI3di0xLjg4OTE0N2gtLjAyMjIyelwiIGZpbGw9XCIjMTkxOTE5XCIvPlxuICAgICAgPHBhdGggY2xhc3M9XCJ0cC1sb2dvX19zdGFyXCIgZmlsbD1cIiMwMEI2N0FcIiBkPVwiTTMwLjE0MTcwNyAxMS4wNzAwNUgxOC42MzE2NEwxNS4wNzY0MDguMTc3MDcxbC0zLjU2NjM0MiAxMC44OTI5NzdMMCAxMS4wNTkwMDJsOS4zMjEzNzYgNi43MzkwNjMtMy41NjYzNDMgMTAuODgxOTMgOS4zMjEzNzUtNi43MjgwMTYgOS4zMTAyNjYgNi43MjgwMTYtMy41NTUyMzMtMTAuODgxOTMgOS4zMTAyNjYtNi43MjgwMTZ6XCIvPlxuICAgICAgPHBhdGggY2xhc3M9XCJ0cC1sb2dvX19zdGFyLW5vdGNoXCIgZmlsbD1cIiMwMDUxMjhcIiBkPVwiTTIxLjYzMTM2OSAyMC4yNjE2OWwtLjc5OTkyOC0yLjQ2MzYyNS01Ljc1NTAzMyA0LjE1MzkxNHpcIi8+XG4gICAgPC9zdmc+XG4gIGA7XG59O1xuXG5jb25zdCBsb2dvRGltZW5zaW9ucyA9IHsgd2lkdGg6IDEyNiwgaGVpZ2h0OiAzMSB9O1xuXG5leHBvcnQgY29uc3QgbG9nbyA9ICgpID0+IHdyYXBTdmcobG9nb0RpbWVuc2lvbnMsIGljb24pO1xuIiwiaW1wb3J0IHsgd3JhcFN2Zywgc3ZnU3RhclN0eWxlIH0gZnJvbSAnLi9oZWxwZXJzJztcbmltcG9ydCB7IGRlZmF1bHRMb2NhbGUsIGZvcm1hdExvY2FsZSwgZ2V0RnJhbWV3b3JrVHJhbnNsYXRpb24gfSBmcm9tICcuLi90cmFuc2xhdGlvbnMnO1xuXG5jb25zdCBlbXB0eVN0YXJDb2xvciA9ICcjZGNkY2U2JztcblxuY29uc3QgaWNvbiA9IChkaW1lbnNpb25zLCB7IHJhdGluZywgdHJ1c3RTY29yZSwgY29sb3IsIHRyYW5zbGF0aW9ucywgbG9jYWxlID0gZGVmYXVsdExvY2FsZSB9KSA9PiB7XG4gIGNvbnN0IHRpdGxlSWQgPSBgc3RhclJhdGluZy0ke01hdGgucmFuZG9tKCkudG9TdHJpbmcoMzYpLnN1YnN0cmluZygyKX1gO1xuICBjb25zdCBpbnRlcnBvbGF0aW9ucyA9IHsgJ1tyYXRpbmdTdGFyc10nOiB0cnVzdFNjb3JlLCAnW3RvdGFsU3RhcnNdJzogNSB9O1xuICBjb25zdCB0cmFuc2xhdGVkU3RhclJhdGluZyA9IGdldEZyYW1ld29ya1RyYW5zbGF0aW9uKCdzdGFyUmF0aW5nJywgdHJhbnNsYXRpb25zLCBpbnRlcnBvbGF0aW9ucyk7XG4gIGNvbnN0IGZvcm1hdHRlZExvY2FsZSA9IGZvcm1hdExvY2FsZShsb2NhbGUpO1xuXG4gIHJldHVybiBgXG4gICAgPHN2ZyByb2xlPVwiaW1nXCIgdmlld0JveD1cIjAgMCAke2RpbWVuc2lvbnMud2lkdGh9ICR7XG4gICAgZGltZW5zaW9ucy5oZWlnaHRcbiAgfVwiIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiAke3N2Z1N0YXJTdHlsZX0+XG4gICAgICA8dGl0bGUgaWQ9XCIke3RpdGxlSWR9XCIgbGFuZz0ke2Zvcm1hdHRlZExvY2FsZX0+JHt0cmFuc2xhdGVkU3RhclJhdGluZ308L3RpdGxlPlxuICAgICAgPGcgY2xhc3M9XCJ0cC1zdGFyXCI+XG4gICAgICAgICAgPHBhdGggY2xhc3M9XCJ0cC1zdGFyX19jYW52YXNcIiBmaWxsPVwiJHtcbiAgICAgICAgICAgIHJhdGluZyA+PSAxICYmIGNvbG9yID8gY29sb3IgOiBlbXB0eVN0YXJDb2xvclxuICAgICAgICAgIH1cIiBkPVwiTTAgNDYuMzMwMDAyaDQ2LjM3NTU4NlYwSDB6XCIvPlxuICAgICAgICAgIDxwYXRoIGNsYXNzPVwidHAtc3Rhcl9fc2hhcGVcIiBkPVwiTTM5LjUzMzkzNiAxOS43MTE0MzNMMTMuMjMwMjM5IDM4LjgwMDY1bDMuODM4MjE2LTExLjc5NzgyN0w3LjAyMTE1IDE5LjcxMTQzM2gxMi40MTg5NzVsMy44Mzc0MTctMTEuNzk4NjI0IDMuODM3NDE4IDExLjc5ODYyNGgxMi40MTg5NzV6TTIzLjI3ODUgMzEuNTEwMDc1bDcuMTgzNTk1LTEuNTA5NTc2IDIuODYyMTE0IDguODAwMTUyTDIzLjI3ODUgMzEuNTEwMDc1elwiIGZpbGw9XCIjRkZGXCIvPlxuICAgICAgPC9nPlxuICAgICAgPGcgY2xhc3M9XCJ0cC1zdGFyXCI+XG4gICAgICAgICAgPHBhdGggY2xhc3M9XCJ0cC1zdGFyX19jYW52YXNcIiBmaWxsPVwiJHtcbiAgICAgICAgICAgIHJhdGluZyA+PSAyICYmIGNvbG9yID8gY29sb3IgOiBlbXB0eVN0YXJDb2xvclxuICAgICAgICAgIH1cIiBkPVwiTTUxLjI0ODE2IDQ2LjMzMDAwMmg0Ni4zNzU1ODdWMEg1MS4yNDgxNjF6XCIvPlxuICAgICAgICAgIDxwYXRoIGNsYXNzPVwidHAtc3Rhcl9fY2FudmFzLS1oYWxmXCIgZmlsbD1cIiR7XG4gICAgICAgICAgICByYXRpbmcgPj0gMS41ICYmIGNvbG9yID8gY29sb3IgOiBlbXB0eVN0YXJDb2xvclxuICAgICAgICAgIH1cIiBkPVwiTTUxLjI0ODE2IDQ2LjMzMDAwMmgyMy4xODc3OTNWMEg1MS4yNDgxNjF6XCIvPlxuICAgICAgICAgIDxwYXRoIGNsYXNzPVwidHAtc3Rhcl9fc2hhcGVcIiBkPVwiTTc0Ljk5MDk3OCAzMS4zMjk5MUw4MS4xNTA5MDggMzAgODQgMzlsLTkuNjYwMjA2LTcuMjAyNzg2TDY0LjMwMjc5IDM5bDMuODk1NjM2LTExLjg0MDY2Nkw1OCAxOS44NDE0NjZoMTIuNjA1NTc3TDc0LjQ5OTU5NSA4bDMuODk1NjM3IDExLjg0MTQ2Nkg5MUw3NC45OTA5NzggMzEuMzI5OTA5elwiIGZpbGw9XCIjRkZGXCIvPlxuICAgICAgPC9nPlxuICAgICAgPGcgY2xhc3M9XCJ0cC1zdGFyXCI+XG4gICAgICAgICAgPHBhdGggY2xhc3M9XCJ0cC1zdGFyX19jYW52YXNcIiBmaWxsPVwiJHtcbiAgICAgICAgICAgIHJhdGluZyA+PSAzICYmIGNvbG9yID8gY29sb3IgOiBlbXB0eVN0YXJDb2xvclxuICAgICAgICAgIH1cIiBkPVwiTTEwMi41MzIyMDkgNDYuMzMwMDAyaDQ2LjM3NTU4NlYwaC00Ni4zNzU1ODZ6XCIvPlxuICAgICAgICAgIDxwYXRoIGNsYXNzPVwidHAtc3Rhcl9fY2FudmFzLS1oYWxmXCIgZmlsbD1cIiR7XG4gICAgICAgICAgICByYXRpbmcgPj0gMi41ICYmIGNvbG9yID8gY29sb3IgOiBlbXB0eVN0YXJDb2xvclxuICAgICAgICAgIH1cIiBkPVwiTTEwMi41MzIyMDkgNDYuMzMwMDAyaDIzLjE4Nzc5M1YwaC0yMy4xODc3OTN6XCIvPlxuICAgICAgICAgIDxwYXRoIGNsYXNzPVwidHAtc3Rhcl9fc2hhcGVcIiBkPVwiTTE0Mi4wNjY5OTQgMTkuNzExNDMzTDExNS43NjMyOTggMzguODAwNjVsMy44MzgyMTUtMTEuNzk3ODI3LTEwLjA0NzMwNC03LjI5MTM5MWgxMi40MTg5NzVsMy44Mzc0MTgtMTEuNzk4NjI0IDMuODM3NDE3IDExLjc5ODYyNGgxMi40MTg5NzV6TTEyNS44MTE1NiAzMS41MTAwNzVsNy4xODM1OTUtMS41MDk1NzYgMi44NjIxMTMgOC44MDAxNTItMTAuMDQ1NzA4LTcuMjkwNTc2elwiIGZpbGw9XCIjRkZGXCIvPlxuICAgICAgPC9nPlxuICAgICAgPGcgY2xhc3M9XCJ0cC1zdGFyXCI+XG4gICAgICAgICAgPHBhdGggY2xhc3M9XCJ0cC1zdGFyX19jYW52YXNcIiBmaWxsPVwiJHtcbiAgICAgICAgICAgIHJhdGluZyA+PSA0ICYmIGNvbG9yID8gY29sb3IgOiBlbXB0eVN0YXJDb2xvclxuICAgICAgICAgIH1cIiBkPVwiTTE1My44MTU0NTggNDYuMzMwMDAyaDQ2LjM3NTU4NlYwaC00Ni4zNzU1ODZ6XCIvPlxuICAgICAgICAgIDxwYXRoIGNsYXNzPVwidHAtc3Rhcl9fY2FudmFzLS1oYWxmXCIgZmlsbD1cIiR7XG4gICAgICAgICAgICByYXRpbmcgPj0gMy41ICYmIGNvbG9yID8gY29sb3IgOiBlbXB0eVN0YXJDb2xvclxuICAgICAgICAgIH1cIiBkPVwiTTE1My44MTU0NTggNDYuMzMwMDAyaDIzLjE4Nzc5M1YwaC0yMy4xODc3OTN6XCIvPlxuICAgICAgICAgIDxwYXRoIGNsYXNzPVwidHAtc3Rhcl9fc2hhcGVcIiBkPVwiTTE5My4zNDgzNTUgMTkuNzExNDMzTDE2Ny4wNDU0NTcgMzguODAwNjVsMy44Mzc0MTctMTEuNzk3ODI3LTEwLjA0NzMwMy03LjI5MTM5MWgxMi40MTg5NzRsMy44Mzc0MTgtMTEuNzk4NjI0IDMuODM3NDE4IDExLjc5ODYyNGgxMi40MTg5NzR6TTE3Ny4wOTI5MiAzMS41MTAwNzVsNy4xODM1OTUtMS41MDk1NzYgMi44NjIxMTQgOC44MDAxNTItMTAuMDQ1NzA5LTcuMjkwNTc2elwiIGZpbGw9XCIjRkZGXCIvPlxuICAgICAgPC9nPlxuICAgICAgPGcgY2xhc3M9XCJ0cC1zdGFyXCI+XG4gICAgICAgICAgPHBhdGggY2xhc3M9XCJ0cC1zdGFyX19jYW52YXNcIiBmaWxsPVwiJHtcbiAgICAgICAgICAgIHJhdGluZyA9PT0gNSAmJiBjb2xvciA/IGNvbG9yIDogZW1wdHlTdGFyQ29sb3JcbiAgICAgICAgICB9XCIgZD1cIk0yMDUuMDY0NDE2IDQ2LjMzMDAwMmg0Ni4zNzU1ODdWMGgtNDYuMzc1NTg3elwiLz5cbiAgICAgICAgICA8cGF0aCBjbGFzcz1cInRwLXN0YXJfX2NhbnZhcy0taGFsZlwiIGZpbGw9XCIke1xuICAgICAgICAgICAgcmF0aW5nID49IDQuNSAmJiBjb2xvciA/IGNvbG9yIDogZW1wdHlTdGFyQ29sb3JcbiAgICAgICAgICB9XCIgZD1cIk0yMDUuMDY0NDE2IDQ2LjMzMDAwMmgyMy4xODc3OTNWMGgtMjMuMTg3NzkzelwiLz5cbiAgICAgICAgICA8cGF0aCBjbGFzcz1cInRwLXN0YXJfX3NoYXBlXCIgZD1cIk0yNDQuNTk3MDIyIDE5LjcxMTQzM2wtMjYuMzAyOSAxOS4wODkyMTggMy44Mzc0MTktMTEuNzk3ODI3LTEwLjA0NzMwNC03LjI5MTM5MWgxMi40MTg5NzRsMy44Mzc0MTgtMTEuNzk4NjI0IDMuODM3NDE4IDExLjc5ODYyNGgxMi40MTg5NzV6bS0xNi4yNTU0MzYgMTEuNzk4NjQybDcuMTgzNTk1LTEuNTA5NTc2IDIuODYyMTE0IDguODAwMTUyLTEwLjA0NTcwOS03LjI5MDU3NnpcIiBmaWxsPVwiI0ZGRlwiLz5cbiAgICAgIDwvZz5cbiAgICA8L3N2Zz5cbiAgYDtcbn07XG5cbmNvbnN0IHN0YXJzRGltZW5zaW9ucyA9IHsgd2lkdGg6IDI1MSwgaGVpZ2h0OiA0NiB9O1xuXG5leHBvcnQgY29uc3Qgc3RhcnMgPSAocHJvcHMpID0+IHdyYXBTdmcoc3RhcnNEaW1lbnNpb25zLCBpY29uLCBwcm9wcyk7XG4iLCJjb25zdCBkZWZhdWx0TG9jYWxlID0gJ2VuLVVTJztcblxuY29uc3QgTE9DQUxFX0RJVklERVIgPSAnLSc7XG5cbmNvbnN0IGxhbmd1YWdlVG9Db3VudHJ5TWFwID0ge1xuICBkYTogJ0RLJyxcbiAgZW46ICdVUycsXG4gIGphOiAnSlAnLFxuICBuYjogJ05PJyxcbiAgc3Y6ICdTRScsXG4gIC8vIG90aGVyIGxhbmd1YWdlcyBhc3N1bWVkIHRvIGhhdmUgdGhlIHNhbWUgY291bnRyeSBjb2RlIGFzIHRoZSBsYW5ndWFnZSBjb2RlIGl0c2VsZlxufTtcblxuLyoqXG4gKiBUcmllcyB0byBmaW5kIHRoZSBjb3VudHJ5IGZvciB0aGUgZ2l2ZW4gbGFuZ3VhZ2UuXG4gKiBJZiBubyBjb3VudHJ5IGlzIGZvdW5kLCB0aGUgbGFuZ3VhZ2UgaXRzZWxmIGlzIHJldHVybmVkLlxuICogQWN0cyBhcyBhIHNhZmV0eSBtZWNoYW5pc20gZm9yIHRyYW5zbGF0aW9ucyB3aGVuIG9ubHkgdGhlIGxhbmd1YWdlIHBhcnQgaXMgcHJlc2VudCBpbiB0aGUgZ2l2ZW4gbG9jYWxlLlxuICpcbiAqIEBwYXJhbSB7c3RyaW5nfSBsYW5ndWFnZSB0aGUgbGFuZ3VhZ2UgZm9yIHdoaWNoIHRoZSBjb3VudHJ5IHNob3VsZCBiZSBmb3VuZC5cbiAqL1xuY29uc3QgdHJ5R2V0Q291bnRyeUZvckxhbmd1YWdlID0gKGxhbmd1YWdlKSA9PiB7XG4gIGNvbnN0IGNvdW50cnkgPSBsYW5ndWFnZVRvQ291bnRyeU1hcFtsYW5ndWFnZV0gfHwgbGFuZ3VhZ2U7XG4gIHJldHVybiBjb3VudHJ5O1xufTtcblxuY29uc3QgZm9ybWF0TG9jYWxlID0gKGxvY2FsZSkgPT4ge1xuICBpZiAoIWxvY2FsZSkgcmV0dXJuIGRlZmF1bHRMb2NhbGU7XG4gIGNvbnN0IGxvY2FsZVBhcnRzID0gbG9jYWxlLnNwbGl0KExPQ0FMRV9ESVZJREVSKTtcbiAgY29uc3QgbGFuZ3VhZ2UgPSBsb2NhbGVQYXJ0c1swXTtcbiAgbGV0IGNvdW50cnkgPSBsb2NhbGVQYXJ0c1sxXTtcblxuICBpZiAoIWNvdW50cnkpIHtcbiAgICBjb3VudHJ5ID0gdHJ5R2V0Q291bnRyeUZvckxhbmd1YWdlKGxhbmd1YWdlKTtcbiAgfVxuXG4gIHJldHVybiBsYW5ndWFnZSAmJiBjb3VudHJ5XG4gICAgPyBgJHtsYW5ndWFnZX0ke0xPQ0FMRV9ESVZJREVSfSR7Y291bnRyeS50b1VwcGVyQ2FzZSgpfWBcbiAgICA6IGRlZmF1bHRMb2NhbGU7XG59O1xuXG5jb25zdCBsb29rdXBUcmFuc2xhdGlvbiA9IChrZXlQYXJ0cywgdHJhbnNsYXRpb25UYWJsZSkgPT4ge1xuICByZXR1cm4ga2V5UGFydHMucmVkdWNlKChhLCBiKSA9PiAoYSAmJiBhW2JdID8gYVtiXSA6ICcnKSwgdHJhbnNsYXRpb25UYWJsZSB8fCB7fSk7XG59O1xuXG5jb25zdCBnZXRSYXdUcmFuc2xhdGlvbiA9IChrZXksIHRyYW5zbGF0aW9uVGFibGUpID0+IHtcbiAgY29uc3Qga2V5UGFydHMgPSBrZXkuc3BsaXQoJy4nKTtcbiAgcmV0dXJuIGxvb2t1cFRyYW5zbGF0aW9uKGtleVBhcnRzLCB0cmFuc2xhdGlvblRhYmxlKTtcbn07XG5cbmNvbnN0IGdldEZyYW1ld29ya1RyYW5zbGF0aW9uID0gKGtleSwgdHJhbnNsYXRpb25UYWJsZSwgaW50ZXJwb2xhdGlvbnMgPSB7fSwgbGlua3MgPSBbXSkgPT4ge1xuICBjb25zdCByYXdUcmFuc2xhdGlvbiA9IGdldFJhd1RyYW5zbGF0aW9uKGtleSwgdHJhbnNsYXRpb25UYWJsZSk7XG4gIGNvbnN0IHRyYW5zbGF0aW9uID0gT2JqZWN0LmtleXMoaW50ZXJwb2xhdGlvbnMpLnJlZHVjZShcbiAgICAodmFsdWUsIGtleSkgPT4gdmFsdWUucmVwbGFjZShrZXksIGludGVycG9sYXRpb25zW2tleV0pLFxuICAgIHJhd1RyYW5zbGF0aW9uXG4gICk7XG4gIGNvbnN0IHRyYW5zbGF0aW9uV2l0aExpbmtzUmVwbGFjZWQgPSBsaW5rcy5yZWR1Y2UoXG4gICAgKHByZXZpb3VzLCBjdXJyZW50KSA9PiBwcmV2aW91cy5yZXBsYWNlKCdbTElOSy1FTkRdJywgJzwvYT4nKS5yZXBsYWNlKCdbTElOSy1CRUdJTl0nLCBjdXJyZW50KSxcbiAgICB0cmFuc2xhdGlvblxuICApO1xuXG4gIHJldHVybiB0cmFuc2xhdGlvbldpdGhMaW5rc1JlcGxhY2VkO1xufTtcblxuZXhwb3J0IHsgZGVmYXVsdExvY2FsZSwgZm9ybWF0TG9jYWxlLCBnZXRGcmFtZXdvcmtUcmFuc2xhdGlvbiB9O1xuIiwiaW1wb3J0IHsgc2FuaXRpemVIdG1sUHJvcCB9IGZyb20gJy4uL3V0aWxzJztcblxuY29uc3QgZmxhdHRlbiA9IChhcnJzKSA9PiBbXS5jb25jYXQuYXBwbHkoW10sIGFycnMpO1xuXG5jb25zdCBta1Byb3BzID0gKHByb3BzKSA9PlxuICBPYmplY3Qua2V5cyhwcm9wcylcbiAgICAubWFwKChrZXkpID0+IHtcbiAgICAgIGNvbnN0IHNhbml0aXplZFByb3AgPSBzYW5pdGl6ZUh0bWxQcm9wKHByb3BzW2tleV0pO1xuICAgICAgcmV0dXJuIGAke2tleX09XCIke3Nhbml0aXplZFByb3B9XCJgO1xuICAgIH0pXG4gICAgLmpvaW4oJyAnKTtcblxuY29uc3QgbWtFbGVtID1cbiAgKHRhZykgPT5cbiAgKHByb3BzLCAuLi5jaGlsZHJlbikgPT4ge1xuICAgIHJldHVybiBgPCR7dGFnfSAke21rUHJvcHMocHJvcHMpfT4ke2ZsYXR0ZW4oY2hpbGRyZW4pLmpvaW4oJ1xcbicpfTwvJHt0YWd9PmA7XG4gIH07XG5cbmNvbnN0IG1rTm9uQ2xvc2luZ0VsZW0gPSAodGFnKSA9PiAocHJvcHMpID0+IGA8JHt0YWd9ICR7bWtQcm9wcyhwcm9wcyl9PmA7XG5cbmNvbnN0IGEgPSBta0VsZW0oJ2EnKTtcbmNvbnN0IGRpdiA9IG1rRWxlbSgnZGl2Jyk7XG5jb25zdCBpbWcgPSBta0VsZW0oJ2ltZycpO1xuY29uc3QgbGFiZWwgPSBta0VsZW0oJ2xhYmVsJyk7XG5jb25zdCBzcGFuID0gbWtFbGVtKCdzcGFuJyk7XG5jb25zdCBpbnB1dCA9IG1rTm9uQ2xvc2luZ0VsZW0oJ2lucHV0Jyk7XG5jb25zdCBvYmplY3QgPSBta0VsZW0oJ29iamVjdCcpO1xuY29uc3QgY3VzdG9tRWxlbWVudCA9IG1rRWxlbTtcblxuY29uc3QgbWtFbGVtV2l0aFN2ZyA9IChzdmcsIGNsYXNzTmFtZSA9ICcnLCBwcm9wcyA9IHt9KSA9PiB7XG4gIGNvbnN0IHsgYXJpYUhpZGVTdmcsIC4uLm90aGVyUHJvcHMgfSA9IHByb3BzO1xuICBjb25zdCBhcmlhSGlkZGVuID0gYXJpYUhpZGVTdmcgPyB7ICdhcmlhLWhpZGRlbic6ICd0cnVlJyB9IDoge307XG4gIHJldHVybiBkaXYoeyBjbGFzczogY2xhc3NOYW1lLCAuLi5hcmlhSGlkZGVuIH0sIHN2ZyhvdGhlclByb3BzKSk7XG59O1xuXG5leHBvcnQgeyBhLCBkaXYsIGltZywgbGFiZWwsIGlucHV0LCBzcGFuLCBvYmplY3QsIG1rRWxlbVdpdGhTdmcsIGN1c3RvbUVsZW1lbnQgfTtcbiJdfQ==
