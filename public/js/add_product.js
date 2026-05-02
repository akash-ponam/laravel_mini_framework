document.addEventListener("DOMContentLoaded",()=>{

    console.log('script load success !!!!');

    const add_form = document.getElementById('add_form');

    // const btn_add_product =  document.getElementById('btn_add_product');




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
