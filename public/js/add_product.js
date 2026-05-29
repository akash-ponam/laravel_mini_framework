document.addEventListener("DOMContentLoaded",()=>{

    console.log('script load success !!!!');

    const add_form = document.getElementById('add_form');

    const dialog = document.getElementById('specsDialog');
    const openBtn = document.getElementById('openDialogBtn');
    const closeBtn = document.getElementById('closeDialogBtn');
    const addPairBtn = document.getElementById('addPairBtn');
    const saveSpecsBtn = document.getElementById('saveSpecsBtn');
    const container = document.getElementById('pairsContainer');
    const textarea = document.getElementById('specs');



    // const btn_add_product =  document.getElementById('btn_add_product');



    const add_row = (key='',value='')=>{

    const row = document.createElement('div');
    row.style.marginBottom = '10px';
    row.className = 'spec-row';
    
    row.innerHTML = `
        <input type="text" placeholder="Key (e.g. Color)" class="spec-key" value="${key}" required style="width: 120px;"> : 
        <input type="text" placeholder="Value (e.g. Red, Blue)" class="spec-value" value="${value}" required style="width: 180px;">
        <button type="button" class="remove-btn" style="color: red; border: none; background: none; cursor: pointer;">❌</button>
    `;
    
    // Delete row event
    row.querySelector('.remove-btn').addEventListener('click', () => row.remove());

    container.appendChild(row);


}

openBtn.addEventListener('click', () => {
    container.innerHTML = ''; // Clear previous fields
    
    console.log('BUTTON IS CLICKED ');


    try {
        if (textarea.value.trim() !== '') {
            const currentData = JSON.parse(textarea.value);
            // Loop through existing JSON and rebuild the form inputs
            for (const [key, val] of Object.entries(currentData)) {
                // If value is an array, join it with commas for easy editing
                const displayVal = Array.isArray(val) ? val.join(', ') : val;
                add_row(key, displayVal);
            }
        } else {
            add_row(); // Add one blank row by default if empty
        }
    } catch (e) {
        add_row(); // Fallback if JSON was corrupted
    }
    
    dialog.showModal();
});


// Add new empty row inside the dialog
addPairBtn.addEventListener('click', () => add_row());

// Close modal without saving
closeBtn.addEventListener('click', () => dialog.close());

// Compile inputs back into JSON string and update textarea
saveSpecsBtn.addEventListener('click', () => {
    const rows = document.querySelectorAll('.spec-row');
    const resultObject = {};
    
    rows.forEach(row => {
        const key = row.querySelector('.spec-key').value.trim();
        const rawValue = row.querySelector('.spec-value').value.trim();
        
        if (key) {
            // Smart value detection: if user typed commas (like "red, blue"), split it into an array
            if (rawValue.includes(',')) {
                resultObject[key] = rawValue.split(',').map(item => item.trim());
            } else {
                resultObject[key] = rawValue;
            }
        }
    });
    
    // Generate perfectly formatted JSON string (with double quotes!)
    textarea.value = JSON.stringify(resultObject);
    dialog.close();
});

















    const addProduct = async(e) =>{

        e.preventDefault();

        const form_data = new FormData(add_form);
        try{ 
            const http_response = await fetch('/api/add_product',{method:'POST',body:form_data});

            if(http_response.ok) {


                const json_response = await http_response.json();

                console.log("response is ",json_response);

                if(json_response.status == 'success') {

                    window.location.replace("/products.html");


                }



            } }catch(eror){

                console.log( " ERROR WHILE ADDING NEW PRODUCT ",eror);
            }




    } 






    add_form.addEventListener("submit",addProduct);

})
