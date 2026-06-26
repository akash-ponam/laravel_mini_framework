console.log("script load success")




document.addEventListener("DOMContentLoaded",()=>{


const path = window.location.pathname; // e.g., "/route/123"
const cart_id = path.split('/').pop(); 

console.log(' cart_id is ',cart_id);




    const cart_heading_row  = document.querySelector(".table_head_row");

    const table_body =  document.querySelector(".cart_body");

    const place_order_button = document.querySelector('.place_order_button');


    var colums = null;

    var cart_items = null;

    const render_header = (list) =>{

        cart_heading_row.innerHTML='';

         columns = [... new Set(list.flatMap(obj=>Object.keys(obj)))];

        console.log('columns are ',columns);

        columns.forEach(column =>{


            cart_heading_row.innerHTML+=`

            <th class="cart_item_heading">${column}

            </th>

            `




        })



    }

    const handle_delete = async(e) =>{


        const target_id = e.target.id.split('-')[1];

        console.log("ITEM CLICKED ",target_id);

        try {


            const http_response = await fetch(`/api/carts/delete/${cart_id}/${target_id}` ,{method:"DELETE"});

            if(http_response.ok){


                const json_response = await http_response.json();

                if(json_response.status =="success") {






                    window.location.href=`/carts/${cart_id}`;



                }



            }




            
        } catch (error) {
            
        }






    }


    window.handle_delete = handle_delete


    const render_rows  = () => {

    
        table_body.innerHTML='';

        if(cart_items){

        
            cart_items.forEach((cart_item) =>

            
                table_body.innerHTML+=`
                
                <tr class="cart_items_entries">

                <td>${cart_item.cart_id}</td>
                
                <td>${cart_item.product_id}</td>

                <td>${cart_item.product}</td>

                <td>${cart_item.price}</td>

                <td>${cart_item.quantity}</td>

                <td>${cart_item.product_purchase}</td>

                <td> 

                <button id ="cart_item-${cart_item.product_id}" onclick="handle_delete(event)">Delete </button>

                </td>

                <tr>

                `






            )







        }else {


            table_body.innerHTML='<h1> error loading table </h1>';


        }
        








    }





    
    const fetch_cart_info =  async () =>{


        const http_response = await fetch(`/api/carts/${cart_id}`);

        if(http_response.ok){


            const json_response = await http_response.json();

             cart_items= json_response.data.cart_items;

            if(cart_items){

                place_order_button.classList.remove("hidden");

            }

            render_header(cart_items);

            render_rows();


        }







    }

    const place_order = async(e) => {

        

        const http_response = await fetch('/api/place_order');
    
        if(http_response.ok){


            const json_response = await http_response.json()

            console.log(json_response.data);



        }
            





    }


    const handle_place_order = async ()=> {

    
        place_order_button.addEventListener("click",place_order);






    }







fetch_cart_info();

handle_place_order();




})
