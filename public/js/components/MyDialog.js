
export class MyDialog {

    constructor(settings){

        this.settings = {
        type:'modals',
        
        confirm_text : 'Confirm',
      

        onConfirm :()=>{}
        ,...settings
        };

        this.dialogElement = null;
            
        this.init();



    }


init() {

this.dialogElement = document.createElement('dialog');

this.dialogElement.className ='custom_dialog_box';

  this.dialogElement.innerHTML = `
      <div class="dialog-wrapper">
        <h3 class="dialog-title">${this.settings.title}</h3>
        <p class="dialog-message">${this.settings.message}</p>
        <div class="dialog-actions">
          <button class="dialog-btn cancel-btn">Cancel</button>
          <button class="dialog-btn confirm-btn">${this.settings.confirmText}</button>
        </div>
      </div>
    `;


document.body.appendChild(this.dialogElement);



this.setupEvents();

}


setupEvents(){

const cancelBtn = this.dialogElement.querySelector('.cancel-btn');
    const confirmBtn = this.dialogElement.querySelector('.confirm-btn');

    // Handle cancel/close
    cancelBtn.addEventListener('click', () => this.destroy());
    
    // Handle built-in 'Esc' key event to ensure cleanup happens
    this.dialogElement.addEventListener('cancel', () => this.destroy());

    // Handle confirm action
    confirmBtn.addEventListener('click', () => {
      this.settings.onConfirm();
      this.destroy(); // Auto-clean after confirming
    });


}


show() {
    // Open based on the context type requested
    if (this.settings.type === 'modal') {
      this.dialogElement.showModal();
    } else {
      this.dialogElement.show();
    }
  }

  destroy() {
    // Close the dialog UI
    this.dialogElement.close();
    // Completely remove the element from the DOM to avoid cluttering memory
    this.dialogElement.remove();
  }




}
