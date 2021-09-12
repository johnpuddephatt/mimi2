require("./index.css").toString();

export default class TranslationBlock {
  constructor({ data, config, api, readOnly }) {
    this.api = api;
    this.data = data || {};

    this.wrapper = null;
    this.content = null;

    this.originalPlaceholder =
      config.originalPlaceholder || TranslationBlock.ORIGINAL_TEXT_PLACEHOLDER;
    this.translationPlaceholder =
      config.translationPlaceholder ||
      TranslationBlock.TRANSLATION_TEXT_PLACEHOLDER;

    this._CSS = {
      wrapperElement: "cdx-translation-block-wrapper",
      contentElement: "cdx-translation-block-content",
      blockWrapper: "cdx-translation-block",
      originalInput: "cdx-translation-block--original",
      translatedInput: "cdx-translation-block--translated",
      deleteButton: "cdx-translation-block--delete-button"
    };
  }
  static get toolbox() {
    return {
      title: "Translation",
      icon:
        '<svg width="17" height="15" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" /></svg>'
    };
  }

  static get ORIGINAL_TEXT_PLACEHOLDER() {
    return "Original text";
  }

  static get TRANSLATION_TEXT_PLACEHOLDER() {
    return "Translated text";
  }

  createNewRow(original, translated) {
    let block = document.createElement("div");
    block.classList.add(this._CSS.blockWrapper);

    let originalInput = document.createElement("div");
    originalInput.classList.add(this._CSS.originalInput);
    originalInput.contentEditable = true;
    originalInput.innerHTML = original ?? null;
    originalInput.dataset.placeholder = this.originalPlaceholder;

    let translationInput = document.createElement("div");
    translationInput.classList.add(this._CSS.translatedInput);
    translationInput.innerHTML = translated ?? null;
    translationInput.contentEditable = true;
    translationInput.dataset.placeholder = this.translationPlaceholder;

    let deleteButton = document.createElement("button");
    deleteButton.ariaLabel = "Delete";
    deleteButton.innerHTML =
      '<svg xmlns="http://www.w3.org/2000/svg" width="17" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
    deleteButton.classList.add(this._CSS.deleteButton);
    deleteButton.addEventListener("click", () => {
      deleteButton.parentNode.remove();
    });

    block.appendChild(originalInput);
    block.appendChild(translationInput);
    block.appendChild(deleteButton);
    // let content = document.querySelector(`.${this._CSS.contentElement}`);
    this.content.appendChild(block);
  }

  render() {
    this.wrapper = document.createElement("div");
    this.wrapper.classList.add(this._CSS.wrapperElement);

    this.content = document.createElement("div");
    this.content.classList.add(this._CSS.contentElement);

    let button = document.createElement("button");
    button.textContent = "Add new";
    button.classList.add("button");
    button.addEventListener("click", () => {
      this.createNewRow();
    });

    this.wrapper.appendChild(this.content);
    this.wrapper.appendChild(button);

    if (this.data && this.data.content && this.data.content.length) {
      this.data.content.forEach(row => {
        this.createNewRow(row.original, row.translated);
      });
    } else {
      this.createNewRow();
    }

    return this.wrapper;
  }

  save() {
    const content = [];

    let entries = this.wrapper.querySelectorAll(`.${this._CSS.blockWrapper}`);

    entries.forEach(entry => {
      content.push({
        original: entry.querySelector(`.${this._CSS.originalInput}`).innerHTML,
        translated: entry.querySelector(`.${this._CSS.translatedInput}`)
          .innerHTML
      });
    });

    let result = {
      // withHeadings: this.data.withHeadings,
      content: content
    };

    return result;
  }
}
