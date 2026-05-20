import React from 'react';

export default function EmbedDetector() {
  React.useEffect(function () {
    function apply() {
      if (window.self !== window.top || sessionStorage.getItem('si-embed')) {
        try { sessionStorage.setItem('si-embed', '1'); } catch (e) {}
        document.documentElement.classList.add('si-embed');
      }
    }
    apply();
    var observer = new MutationObserver(function () {
      if (!document.documentElement.classList.contains('si-embed') && sessionStorage.getItem('si-embed')) {
        document.documentElement.classList.add('si-embed');
      }
    });
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    return function () { observer.disconnect(); };
  }, []);

  return React.createElement('div', { style: { display: 'none' }, 'data-si-embed-detector': 'true' });
}