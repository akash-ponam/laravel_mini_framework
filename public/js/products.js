
import { MyDialog } from "./components/MyDialog.js";


document.addEventListener("DOMContentLoaded",()=>{


    var cart = [];

    var comparable_items = [];

    const url_params = {
        page:1,
        per_page:10,
        sort:'price_asc'

    }
 

    
    const dialog_container =  document.querySelector(".compare_dialog") ;

    const close_comparison_button =  document.querySelector('.close_dialog');


    const clear_all_button  = document.querySelector('.clear_all');


    clear_all_button.addEventListener("click",(e)=>{

        console.log('clear button clicked');
    
        comparable_items = [];

        console.log(' items cleared ',comparable_items);

        comparison_area.replaceChildren(button_comparison,close_comparison_button,clear_all_button);

        button_comparison.classList.add('hidden');


        comparison_area.classList.remove('is-visible');


})

    
    close_comparison_button.addEventListener("click",(e)=> {

        
        // e.preventDefault();
        
    comparison_area.classList.remove('is-visible');


  //   const deleteAlert = new MyDialog({
  //   title: 'Warning!',
  //   message: 'Are you sure you want to delete this product? This cannot be undone.',
  //   confirmText: 'Yes, Delete',
  //   type: 'modal', // Blocks user interaction
  //   onConfirm: () => {
  //     console.log('Product deleted from database.');
  //     // your actual delete logic here
  //   }
  // });
  //
  // deleteAlert.show();






    })


function checkCommonAttribute(arr, prop) {
  // 1. Extract the specific property values from all objects
  const values = arr.map(obj => obj[prop] );

    console.log('VALUES INPUT ARE  ',values);
  
  // 2. Put them in a Set to get only the unique values
  const uniqueValues = new Set(values);

    
    console.log('UNIOUE VALUES',uniqueValues);
    
  
  // // 3. Check the size of the set to determine the output
  // if (uniqueValues.size === 1) {
  //   return 0; // All have the exact same value
  // } else {
  //   return uniqueValues.size; // Returns 2 if two are different, or 3 if all three are different
  // }
//
//
return uniqueValues.size;
}



        
    const comparison_area = document.querySelector('.comparison_area');

    const button_comparison = document.querySelector('.button_comparison');

    if(!button_comparison){

    console.log(' no comparison button was found');

        
    }
    
    button_comparison.addEventListener("click",(e)=>{

    console.log("BUTTON COMPARISON CLICKED");
        
    const res = checkCommonAttribute(comparable_items,'category')

    if(res > 1 ){

        show_dialog('MISMATCH COMPARISON','items from different Category not comparable');

        
        comparable_items =[];

    
        comparison_area.replaceChildren(button_comparison,close_comparison_button,clear_all_button);
        
                
        comparison_area.classList.remove('is-visible');
     
        return;
    }


    open_comparison_dialog();


    });

    button_comparison.classList.add('hidden');
 
    
    comparison_area.classList.remove('is-visible');


    // comparison_area.style.display='none';


    const search_results_container =  document.querySelector('.search_results_container');

    const search_box_container = document.querySelector('.search_container');

    const search_product_input = document.querySelector('.search_product_input');

    const search_box_coords = search_box_container.getBoundingClientRect();

    const search_box_height = search_box_coords.height;

    const search_box_left = search_box_coords.left;

    const search_box_top = search_box_coords.top;

    search_results_container.style.position = 'absolute';

    search_results_container.style.top= search_box_top+ search_box_height;

    search_results_container.style.left = search_box_left;


    search_results_container.classList.add('hidden');

    const per_page_input = document.querySelector('.per_page_input');


    var products_meta = [];

    const pagination_wrapper = document.querySelector('.pagination_wrapper');


    // const prev_button = document.querySelector('.prev');
    //
    //
    // const next_button  = document.querySelector('.next');



    const grid_ref = document.getElementById('product-grid');

    



    var products_cards  =  [];

    const observer_options = {root:grid_ref ,threshold:0.7};

    var being_sorted = false;

    const btn_asc = document.querySelector('#price_asc');


    console.log('id of asc btn ',btn_asc.id);

    btn_asc.classList.add("active_sort_option");

    const btn_desc  =  document.getElementById('price_desc');


    const handle_sort = (e) =>{


        e.preventDefault();


        url_params.sort= e.target.id==='price_asc'? 'price_asc':'price_desc';

        if(url_params.sort=='price_asc'){

        btn_asc.classList.add("active_sort_option");
        
        btn_desc.classList.remove("active_sort_option");
        
        }else {

    
            btn_desc.classList.add("active_sort_option");


            btn_asc.classList.remove("active_sort_option");



        }



        fetch_data();

    };

    btn_asc.addEventListener("click",handle_sort);

    btn_desc.addEventListener("click",handle_sort);






    function scrollProducts(direction) {


            
            const scroll_amount = 332;


        grid_ref.scrollBy({


            left:scroll_amount * direction,
            behavior:'smooth'

        })



    }



    function open_comparison_dialog() {

    if(comparable_items.length<=1){
        
        show_dialog('COMPARE ','ADD MORE ITEMS TO COMPARE LIST');

        return;

    }

    dialog_container.innerHTML="";

    const current_category = comparable_items[0].category;

    console.log('comparison is being done in category ',current_category);

    let matrix_html = " ";

    // switch (current_category.toLowerCase()) {
    //     case 'outerwear':
    //
    //         matrix_html = compare_clothes(comparable_items);
    //
    //         break;
    //
    //     case 'cars':
    //
    //         matrix_html = compare_clothes(comparable_items);
    //
    //         break;
    //
    //
    //     default:
    //
    //         matrix_html = `comparison layout for ${current_category} is not implemented yet`;
    //
    //         break;
    // }

    matrix_html = compare_clothes(comparable_items);

    dialog_container.innerHTML= matrix_html;

document.querySelector('.close_btn').addEventListener("click",()=>{


dialog_container.classList.add("hidden");


})






    dialog_container.classList.remove("hidden");


        


}


function compare_clothes(clothes) {

const all_attributes = [];

clothes.forEach((cloth)=> {
    
            if(cloth.specs){
            

            all_attributes.push(...Object.keys(cloth.specs));

        }






});


const unique_attributes = [...new Set(all_attributes)];

let html = ` <div class ="modal_content">

    <span class="close_btn">X</span>

    <h2>Category comparison :Clothes </h2>

    <table class="comparison_table">

    <thead>
        <tr>

        <th>Specifications </th>

`;

clothes.forEach(cloth =>{

    html+=`<th>${cloth.name}</th>`;
    

})

html+=`</tr> 
</thead>

<tbody>

`;

unique_attributes.forEach( attr =>{

html+=`<tr>`;

html+=`<td><strong>${attr.toUpperCase()}</strong>`;


clothes.forEach( cloth => {

    if(cloth.specs && cloth.specs[attr]){

        const value  = cloth.specs[attr];

        html += `<td>${Array.isArray(value)?value.join(', '):value} </td>`;



    }else{
        
        html+=`<td> - </td>`;

    }
    



});

html+=`</tr>`;





});

html = html + `</tbody> 
</table>

</div>

`;


return html;




}



















    const render_search_results = (results) =>{

        search_results_container.innerHTML='';

        
       results.forEach(result => {
        
 

        const item = document.createElement('div');

        item.classList.add("item");

        const a = document.createElement('a');

        a.classList.add("potato");

        a.href = `/products/${result.product_id}`;

        a.innerText=result.name;

        item.appendChild(a);

        search_results_container.appendChild(item);




       });

  







    }




    const handle_query = (query ,delay ) => {


        setTimeout( async ()=>{

            const http_response = await fetch(`/api/query_product?search=${query}`);

            if(http_response.ok){


                search_results_container.style.display='block';

                const json_response = await http_response.json();

                const search_results = json_response.data;

                // console.log(" searched results are ",search_results);

                render_search_results(search_results);





            }







        },delay)



    }



    const handle_search = (e) =>{

            

        console.log(
            
            "value is " ,e.target.value
        )

    
        if(e.target.value.length==0){

            search_results_container.style.display='none';


        } else {



            handle_query(e.target.value,500);

        }






    }



    function setup_search() {


        search_product_input.addEventListener("input",handle_search);




    }





    // prev_button.addEventListener("click",(e)=>{
    //
    //
    //     scrollProducts(-1);
    //
    // })
    //
    //
    // next_button.addEventListener('click',(e)=>{
    //
    //
    //
    //     scrollProducts(1);
    //
    //
    // })









    var products = null;

    const event_products_available  = new CustomEvent('ProductsFetched',{detail:{products_fetched:true},bubbles:true})





    const quantity_info = document.querySelector('.quantity');


    const profile_card = document.querySelector('.profile_card');

    const user_logout_btn = document.getElementById('user_logout_btn');

    const user_info = document.getElementById('user_info');


    const profile_options_menu = document.querySelector('.profile_options_menu');



    console.log('script loaded lmfao pro max');








    const logout = async(e) => {

    e.preventDefault();


    const  logout_res_http =  await fetch('/api/logout');

    if(logout_res_http.ok){


       const  logout_res_json =  await logout_res_http.json();

        console.log(' logout response is ',logout_res_json);

        if(logout_res_json.status ==='success'){
            
            console.log('logout ho gya lol');

            localStorage.clear();

            // localStorage.removeItem('user_profile_url');
            //
            // localStorage.removeItem('user_email');
            //
            // localStorage.removeItem('user_loggedin');

            // reset_ui();

            window.location.href="/";


        }


    }





}


user_logout_btn.addEventListener("click",logout);





    const render_profile = () => {

        const image_src  = localStorage.getItem('user_profile_url');
        console.log('image src is ',image_src);
        if(image_src && profile_card){

        console.log('found profile card reference');
        profile_card.innerHTML=`<img src ="${image_src}" alt="profile" >`;


         user_info.innerText=localStorage.getItem('user_mail');   

        }





    }


    const update_cart_info = () => {



            if(cart.length>0){
                quantity_info.style.display='block';
                quantity_info.innerText=cart.length;
            }else {

                quantity_info.style.display='none';

            }




    }


    const add_to_cart = (product,btn) => {

        const product_index = cart.findIndex( item => item.product_id === product.product_id);

        if(product_index!=-1){ 


            cart.splice(product_index,1);



            console.log(' item exists remving item  cart is ',cart);

        }

        else{

            cart.push({product_id:product.product_id,product_url : product.image_url});

        }


        console.log('current cart is ',cart);


      

    };




    


        const render_product = (product) => {


        var default_text = "ADD TO CART";
    
        const is_in_cart = cart.some(item => item.product_id=== product.product_id);

        if(is_in_cart){

            default_text="REMOVE FROM CART";
        }

        const existing_card = document.querySelector(`#card-${product.product_id}`);

        if(existing_card){


            console.log(' render phase product is ',product);
            console.log(' yup card already exists');



            const btn_cart = existing_card.querySelector(`#btn-${product.product_id}`);


            if(btn_cart) {

                console.log('button exists');

            }




            btn_cart.innerText = default_text;

            return ;



        }

        


        const new_product_card = document.createElement('div');

        // new_product_card.classList.add(`card-${product.product-id}`);



        new_product_card.id = `card-${product.product_id}`;




        new_product_card.classList.add("product-card");

        new_product_card.innerHTML = `  

         <img src="${product.image_url}" >

            <h1>${product.name}</h1>
            <p class="product_price" >${product.price}</p>

            <p class ="product_category"> ${product.category}</p>
 

            <button class="add_to_cart_btn" id="btn-${product.product_id}" >${default_text}</button>

            <button class ="btn-compare" >COMPARE </button>`;

            


            // products_cards.push(new_product_card);

            return new_product_card;



        

        // return `<div class="product-card">
        //
        //     <img src="${product.image_url}" >
        //
        //     <h1>${product.name}</h1>
        //     <p>${product.price}</p>
        //
        //     <button class="add_to_cart_btn" id="btn-${product.product_id}" >${is_in_cart}</button>
        //
        //
        //     </div>
        // `;

    }


const setup_page_nav = () =>{

    per_page_input.addEventListener('input',(e)=>{


        const min_Value =parseFloat(e.target.min);


        


        if(e.target.value.length > 0 ){


            url_params.per_page=e.target.value;



        }

        else {


            url_params.per_page=10;



        }




    })





    pagination_wrapper.addEventListener("click",(e) => {


        const page_btn = e.target.closest('.page_num');


        if(!page_btn){
            return;
        }

        const page_number = page_btn.id.split('-')[1];

        console.log('pagen umber is ',page_number);




        url_params.page=page_number;


        fetch_data();
        






    })





}


const render_page_num = (page_number) => {

    const active_class = ((page_number) == url_params.page)?'active_page':'';


    return `<div class ="page_num ${active_class}" id="page-${page_number}"> <p> ${page_number} </p>  </div>`;




}


const highlight_current_page = () =>{


 const active_page_button =    document.getElementById(`page-${url_params.page}`);


}

const show_dialog = (title,message) =>{

    const deleteAlert = new MyDialog({
    title: title+"!",
    message: message,
    confirmText: 'Ok',
    type: 'modal', // Blocks user interaction
    onConfirm: () => {
      console.log('Product deleted from database.');
      // your actual delete logic here
    }
  });

  deleteAlert.show();


}


const render_comparable_item = (product) => {


    comparable_items.push(product);

    comparison_area.classList.add('is-visible');

    if(comparable_items.length>=2){

        button_comparison.classList.remove('hidden');

    }

    console.log('so far comparable items',comparable_items);

       // 4. Create and append the new item element to the DOM
    const comparable_item = document.createElement('div');
    comparable_item.id = 'cmp-'+product.product_id;
    comparable_item.classList.add("comparable_item");
    comparable_item.innerHTML = `
        <img src="${product.image_url}" alt="${product.title || 'Product'}">
    
        `;
    comparison_area.appendChild(comparable_item)

    ;









}












function add_to_compare(product) {
    // 1. Check if the item is already in the list
    const index = comparable_items.findIndex(p => p.product_id == product.product_id);   
    if (index !== -1) {
        console.log("Item already added to comparison.");
        
        show_dialog('already added','product already added to comparison');

        // comparison_area.classList.add('is-visible');
        return; // Exit early so we don't add duplicates
    }

    // new item to be added sine it does not exist  yet 

    if(comparable_items.length==3){

        show_dialog('LIMIT REACHED',"You can compare max 3 items");
        return;

    }

    render_comparable_item(product);
    


    
}
    



    const fetch_data = async() => {

        comparable_items =[];

        

        console.log('cart at this point is ',cart);

        grid_ref.innerHTML='';

        pagination_wrapper.innerHTML='';
            
        try {

            const http_response =  await fetch(`/api/products?page=${url_params.page}&per_page=${url_params.per_page}&sort=${url_params.sort}`);


            const json_response = await  http_response.json();

            if(http_response.ok){

                if(json_response.status==='success'){

                    console.log('data is ',json_response.data);

                    products =  json_response.data.data;

                    products_meta = json_response.data.meta;

                    for (let i = 0; i < products_meta.total_page; i++) {

                        // let active_class = ((i+1) == url_params.page)?'active_page':'';
                        //
                        //
                        // pagination_wrapper.innerHTML += `<div class ="page_num ${active_class}" id="page-${i+1}"> <p> ${i+1} </p>  </div>`;

                        pagination_wrapper.innerHTML += render_page_num(i+1);

                    }




                    // console.log('inside fetch products is ',products);

                    products.map((p) => {
                        
                        grid_ref.appendChild(render_product(p));
                    })

                    document.dispatchEvent(event_products_available);

                    grid_ref.addEventListener('click',(e)=>{

                        if(e.target.classList.contains('add_to_cart_btn')){


                            // e.stopPropagation();

                            const id = e.target.id.replace('btn-','');


                            const product_ref = products.find(p=>p.product_id == id);

                            console.log(`button ${e.target.id} clicked ` );


                            add_to_cart(product_ref,e.target);

                            return;

                        }



                        const compare_btn  = e.target.closest('.btn-compare');

                        if(!compare_btn){

                            return;

                            
                        }

                        const id = e.target.parentElement.id.replace('card-','');

                        const target_product = products.find(p=>p.product_id == id);

                        console.log('product is ', target_product );

                        add_to_compare(target_product);

                    })




                }else{

                    console.log('data fetch failed');


                }



            }




        

            
        } catch (error) {


            console.log(" EXCEPTION OCCURED ",error);
        }
       
    




        highlight_current_page();

    }



profile_card.addEventListener("click",(e)=>{


    e.preventDefault();

    if(profile_options_menu.style.display=='block'){


        profile_options_menu.style.display='none';

    }
    else {

    
        profile_options_menu.style.display='block';

    }

    console.log('dekho re  dekho aaya re kaun');

    


})


document.addEventListener('keydown', (event) => {
  if(event.key === 'Escape') {
    // console.log('Escape key was pressed!');
  
     if(profile_options_menu.style.display=='block'){


        profile_options_menu.style.display='none';

        console.log(" search input content s ",search_product_input.value);

        dialog_container.classList.add("hidden");

    }

    comparison_area.classList.remove('is-visible');


  }
});

const validate_session = async() =>{


    const http_response = await fetch('/api/session');

    if(http_response.ok){


        const json_response = await http_response.json();
        
        if(json_response.status=='failed') {

            console.log("session timed out");

            window.location.href.replace("/");


        }


    }else{

        window.location.replace("/");
    }


}




validate_session();
profile_options_menu.style.display='none';
render_profile();
fetch_data();
console.log(" look i am out side ",products);

document.addEventListener('ProductsFetched',(e) => {

    
    console.log('data fetch succcess ');






})



setup_page_nav();
setup_search();
});
