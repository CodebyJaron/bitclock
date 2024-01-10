// modal.ts

export class CustomModal {
    private element: HTMLDivElement;
    private isVisible: boolean = false;

    constructor(element: HTMLDivElement) {
      this.element = element;
    }

    show() {
      this.isVisible = true;
      this.element.style.display = 'block';
    }

    hide() {
      this.isVisible = false;
      this.element.style.display = 'none';
    }

    isOpen(): boolean {
      return this.isVisible;
    }
  }

  export function createModal(
    element: HTMLDivElement,
    emit: (event: 'hide') => void
  ): CustomModal {
    const modal = new CustomModal(element);

    element.addEventListener('click', (e) => {
      if (e.target === element) {
        modal.hide();
        emit('hide');
      }
    });

    return modal;
  }
