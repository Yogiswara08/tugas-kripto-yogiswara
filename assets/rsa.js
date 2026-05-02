// Copy text to clipboard
function copyText(btn, text) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.textContent;
        btn.textContent = '✅ Tersalin!';
        setTimeout(() => { btn.textContent = orig; }, 1800);
    }).catch(() => {
        // Fallback for older browsers
        const ta = document.createElement('textarea');
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        const orig = btn.textContent;
        btn.textContent = '✅ Tersalin!';
        setTimeout(() => { btn.textContent = orig; }, 1800);
    });
}
