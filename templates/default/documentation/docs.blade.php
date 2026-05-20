<style nonce="{{ $nonce ?? '' }}">
    /* Override container padding for full-height iframe */
    .docs-wrapper { margin: -2rem -1.5rem; }
    .docs-wrapper iframe { width: 100%; height: calc(100vh - 8rem); border: none; display: block; }
    @media (max-width: 768px) {
        .docs-wrapper { margin: -1rem -0.75rem; }
        .docs-wrapper iframe { height: calc(100vh - 6rem); }
    }
</style>
<div class="docs-wrapper">
    <iframe src="./docs/index.html?embed=1" title="Documentation"></iframe>
</div>
