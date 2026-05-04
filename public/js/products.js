
document.addEventListener("DOMContentLoaded",()=>{


    const prev_button = document.querySelector('.prev');


    const next_button  = document.querySelector('.next');





    const grid_ref = document.getElementById('product-grid');

    var products_cards  =  [];







    const observer_options = {root:grid_ref ,threshold:0.7};

    console.log('dunno wtf is going on ');


    //
    // const observer  = new IntersectionObserver((enteries) =>{
    //
    //
    //     enteries.forEach(e => {
    //
    //
    //     console.log("Observer target:", e.target);
    //     console.log("Is it intersecting?", e.isIntersecting);
    //     console.log("How much is visible?", e.intersectionRatio);
    //
    //         if(e.isIntersecting){
    //
    //             e.target.classList.add("active");
    //             console.log("its on centre lol");
    //         }else{
    //             e.target.classList.remove("active");
    //         }
    //
    //
    //     } );
    // },observer_options
    //
    // );
    //

    //
    // const setup_observer =()=> {
    //
    //
    //
    //
    // products_cards.forEach(card => observer.observe(card));
    //
    //
    //
    //
    //
    //
    //
    // }



    function scrollProducts(direction) {


            
            const scroll_amount = 332;


        grid_ref.scrollBy({


            left:scroll_amount * direction,
            behavior:'smooth'

        })






    }


    prev_button.addEventListener("click",(e)=>{


        scrollProducts(-1);

    })


    next_button.addEventListener('click',(e)=>{


    
        scrollProducts(1);


    })









    var products = null;

    const event_products_available  = new CustomEvent('ProductsFetched',{detail:{products_fetched:true},bubbles:true})



    var cart = [];


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





    render_profile = () => {

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

    
        if(cart.includes(product)){

            cart.pop();
            
            update_cart_info();

            btn.innerText="ADD TO CART";


        }else{


            cart.push(product);

            update_cart_info();

            
            btn.innerText="REMOVE FROM CART";


           




        }
          
        




    }




    const render_product = (product) => {


        
        const is_in_cart = cart.includes(product)?"REMOVE FROM CART":"ADD TO CART";
       

        const new_product_card = document.createElement('div');

        new_product_card.classList.add("product-card");

        new_product_card.innerHTML = `  

         <img src="${product.image_url}" >

            <h1>${product.name}</h1>
            <p class="product_price" >${product.price}</p>
            
            <button class="add_to_cart_btn" id="btn-${product.product_id}" >${is_in_cart}</button>`;



        products_cards.push(new_product_card);

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


    
    



    const fetch_data = async() => {

            
        try {

        const http_response =  await fetch("/api/products");

            const json_response = await  http_response.json();

            if(http_response.ok){

                if(json_response.status==='success'){

                    console.log('data is ',json_response.data);

                    products =  json_response.data;

                    console.log('inside fetch products is ',products);

                    products.map((p) => {
                        
                        grid_ref.appendChild(render_product(p));
                    })

                    document.dispatchEvent(event_products_available);

                    grid_ref.addEventListener('click',(e)=>{

                        if(e.target.classList.contains('add_to_cart_btn')){

                            const id = e.target.id.replace('btn-','');

                            const product_ref = products.find(p=>p.product_id == id);

                            add_to_cart(product_ref,e.target);

                        }









                    })




                }else{

                    console.log('data fetch failed');


                }



            }




        

            
        } catch (error) {


            console.log(" EXCEPTION OCCURED ",error);
        }
       
    





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
    console.log('Escape key was pressed!');
  
     if(profile_options_menu.style.display=='block'){


        profile_options_menu.style.display='none';

    }



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

});
