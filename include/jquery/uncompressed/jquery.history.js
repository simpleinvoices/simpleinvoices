/**
 * History - jQuery plugin
 *
 * http://stilbuero.de/jquery/history/
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * alpha
 */

jQuery.history = new function() {

    var _hash = location.hash;
    var _intervalId = null;
    var _historyIframe; // for IE
    var _backStack, _forwardStack, _addHistory; // for Safari

    var _observeHistory; // define outside if/else required by Opera
    if (jQuery.browser.msie) {

        // add hidden iframe
        $(function() {
            _historyIframe = $('<iframe style="display: none;"></iframe>').appendTo(document.body)[0];
            var iframeDoc = _historyIframe.contentWindow.document;
            iframeDoc.open();
            iframeDoc.close();
            iframeDoc.location.hash = _hash;
        });

        _observeHistory = function() {
            if (_historyIframe) {
                var iframeDoc = _historyIframe.contentWindow.document;
                var iframeHash = iframeDoc.location.hash;
                if (iframeHash != _hash) {
                    location.hash = _hash = iframeHash;
                    jQuery('a[@href$="' + _hash + '"]').click();
                }
            }
        };

    } else if (jQuery.browser.mozilla || jQuery.browser.opera) {

        _observeHistory = function() {
            if (location.hash) {
                if (_hash != location.hash) {
                    _hash = location.hash;
                    jQuery('a[@href$="' + _hash + '"]').click();
                }
            } else {
                _hash = '';
                var output = jQuery('.remote-output');
                if (output.children().size() > 0) output.empty();
            }
        };

    } else if (jQuery.browser.safari) {

        // etablish back/forward stacks
        $(function() {
            _backStack = [];
            _backStack.length = history.length;
            _forwardStack = [];
        });
        var isFirst = false;
        _addHistory = function(hash) {
            _backStack.push(hash);
            _forwardStack.length = 0; // clear forwardStack (true click occured)
            isFirst = false;
        };

        _observeHistory = function() {
            var historyDelta = history.length - _backStack.length;
            if (historyDelta) { // back or forward button has been pushed
                isFirst = false;
                if (historyDelta < 0) { // back button has been pushed
                    // move items to forward stack
                    for (var i = 0; i < Math.abs(historyDelta); i++) _forwardStack.unshift(_backStack.pop());
                } else { // forward button has been pushed
                    // move items to back stack
                    for (var i = 0; i < historyDelta; i++) _backStack.push(_forwardStack.shift());
                }
                var cachedHash = _backStack[_backStack.length - 1];
                jQuery('a[@href$="' + cachedHash + '"]').click();
                _hash = location.hash;
            } else if (_backStack[_backStack.length - 1] == undefined && !isFirst) {
                // back button has been pushed to beginning and URL already pointed to hash (e.g. a bookmark)
                // document.URL doesn't change in Safari
                if (document.URL.indexOf('#') >= 0) {
                    jQuery('a[@href$="' + '#' + document.URL.split('#')[1] + '"]').click();
                } else {
                    var output = jQuery('.remote-output');
                    if (output.children().size() > 0) output.empty();
                }
                isFirst = true;
            }
        };

    }

    this.setHash = function(hash, e) {
        _hash = hash;
        if (this.iframe()) {
            this.iframe().open();
            this.iframe().close();
            this.iframe().location.hash = hash;
        }
        if (typeof _addHistory == 'function' && e.clientX) _addHistory(_hash); // add to history only if true click occured TODO check keyboard!
    };

    this.iframe = function() {
        var iframeDoc = _historyIframe && _historyIframe.contentWindow.document || null;
        //$.log((new Date().getTime()) + iframeDoc); TODO function is called four times, because location has to be set manually in IE!?
        return iframeDoc;
    };

    this.observe = function() {
        // look for hash in current URL (not Safari)
        if (location.hash && typeof _addHistory == 'undefined') jQuery('a.remote[@href$="' + location.hash + '"]').click(); // TODO filter remote links
        // start observer
        if (_observeHistory && _intervalId == null) _intervalId = setInterval(_observeHistory, 250);
    };

};

/**
 * Register a link that points to a fragment on the same site for history observation.
 * That will fix back and forward button for click events that are attached to that link
 * while maintaining bookmarkability.
 *
 * @example $('a').history();
 * @desc Register a link to be observed for history.
 *
 * @type jQuery
 *
 * @name history
 * @cat Plugins/History
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */
jQuery.fn.history = function() {
    return this.each(function() {
        jQuery(this).click(function(e) {
            jQuery.history.setHash(this.hash, e);
        });
    });
};

/**
 * Hijax links and register them for history observation. The link's href attribute is altered to an hash
 * and a click event handler is attached to the link. This handler loads content from the URL the
 * link was pointing to before altering the href attribute and displays it in a given element.
 *
 * This solution maintains bookmarkability, fixes back and forward button and is totally
 * unobtrusive, i.e. guarantees accessibility in case of JavaScript disabled.
 *
 * @example $('a.remote').remote('#output');
 * @before <a href="/foo/bar.html">Bar</a>
 * @result <a href="#remote-1">Bar</a>
 * @desc Hijax all links with the class "remote" and let them load content from the URL of it's initial
 *       href attribute via XHR into an element with the id 'output'.
 *
 * @param String expr This function accepts a string containing a CSS selector or basic XPath.
 * @type jQuery
 *
 * @name remote
 * @cat Plugins/Remote
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

/**
 * Hijax links and register them for history observation. The link's href attribute is altered to an hash
 * and a click event handler is attached to the link. This handler loads content from the URL the
 * link was pointing to before altering the href attribute and displays it in a given element.
 *
 * This solution maintains bookmarkability, fixes back and forward button and is totally
 * unobtrusive, i.e. guarantees accessibility in case of JavaScript disabled.
 *
 * @example $('a.remote').remote(  $("#foo")[0] );
 * @before <a href="/foo/bar.html">Bar</a>
 * @result <a href="#remote-1">Bar</a>
 * @desc Hijax all links with the class "remote" and let them load content from the URL of it's initial
 *       href attribute via XHR into the element saved in $("#foo")[0].
 *
 * @param DOMElement elem A DOM element to load the content into.
 * @type jQuery
 *
 * @name remote
 * @cat Plugins/Remote
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */
jQuery.fn.remote = function(output) {
    var target = jQuery(output).size() && jQuery(output) || jQuery('<div></div>').appendTo('body');
    target.addClass('remote-output');
    return this.each(function(i) {
        var remote = this.href;
        var hash = 'remote-' + ++i;
        jQuery(this).href('#' + hash).click(function(e) {
            target.load(remote, function() {
                jQuery.history.setHash('#' + hash, e); // setting hash in callback is required to make it work in Safari
            });
        });
    });
};

// for development...
$.log = function(s) {
    var LOG_OUTPUT_ID = 'log-output';
    var LOG_OUTPUT_STYLE = 'position: fixed; _position: absolute; top: 0; right: 0; overflow: hidden; border: 1px solid; width: 300px; height: 200px; background: #fff; color: red; opacity: .95;';
    var logOutput = $('#' + LOG_OUTPUT_ID)[0] || $('<div style="' + LOG_OUTPUT_STYLE + '" id="' + LOG_OUTPUT_ID + '"></div>').prependTo('body')[0];
    $(logOutput).prepend('<code>' + s + '</code><br />');
};