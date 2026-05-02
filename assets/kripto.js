// Algorithm switching
const radioCards = document.querySelectorAll('.radio-card');
const radioInputs = document.querySelectorAll('input[name="algorithm_radio"]');
const algorithmHidden = document.getElementById('algorithm-hidden');
const caesarGroup = document.getElementById('caesar-key-group');
const vigenereGroup = document.getElementById('vigenere-key-group');

function switchAlgorithm(value) {
  algorithmHidden.value = value;
  radioCards.forEach(card => card.classList.remove('active'));
  radioInputs.forEach(radio => {
    if (radio.value === value) {
      radio.checked = true;
      radio.closest('.radio-card').classList.add('active');
    }
  });
  if (value === 'caesar') {
    caesarGroup.style.display = '';
    vigenereGroup.style.display = 'none';
  } else {
    caesarGroup.style.display = 'none';
    vigenereGroup.style.display = '';
  }
}

radioCards.forEach(card => {
  card.addEventListener('click', () => {
    const val = card.querySelector('input[type="radio"]').value;
    switchAlgorithm(val);
  });
});

// Character counter
const textarea = document.getElementById('message');
const charCount = document.getElementById('char-count');
function updateCharCount() { if (charCount) charCount.textContent = textarea.value.length; }
if (textarea) { textarea.addEventListener('input', updateCharCount); updateCharCount(); }

// Number stepper
const keyInput = document.getElementById('caesar_key');
const btnMinus = document.getElementById('btn-minus');
const btnPlus  = document.getElementById('btn-plus');
if (btnMinus && btnPlus && keyInput) {
  btnMinus.addEventListener('click', () => { let v = parseInt(keyInput.value, 10); if (v > 1)  keyInput.value = v - 1; });
  btnPlus.addEventListener('click',  () => { let v = parseInt(keyInput.value, 10); if (v < 25) keyInput.value = v + 1; });
}

// Operation flag
const operationHidden = document.getElementById('operation-hidden');
function setOperation(op) { if (operationHidden) operationHidden.value = op; }

// Copy to clipboard
function copyResult() {
  const text = document.getElementById('result-text');
  const btn  = document.querySelector('.copy-btn');
  if (!text || !btn) return;
  navigator.clipboard.writeText(text.textContent.trim()).then(() => {
    btn.textContent = '✅ Disalin!';
    setTimeout(() => btn.textContent = '📋 Salin', 2000);
  });
}

// Auto-dismiss error
const errorBox = document.getElementById('error-box');
if (errorBox) {
  setTimeout(() => {
    errorBox.style.opacity = '0';
    setTimeout(() => errorBox.remove(), 500);
  }, 4000);
}
