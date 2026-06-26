
document.addEventListener("DOMContentLoaded",()=>{



const path = window.location.pathname; // e.g., "/route/123"
const order_id = path.split('/').pop(); 

console.log(' order_id is ',order_id);

order_detail_heading =  document.querySelector(".order_detail_heading");

order_enteries = document.querySelector(".order_enteries");





    const render_table = (list) =>{

        order_detail_heading.innerHTML='';

        order_enteries.innerHTML=''

         columns = [... new Set(list.flatMap(obj=>Object.keys(obj)))];

        console.log('columns are ',columns);

        columns.forEach(column =>{


            order_detail_heading.innerHTML+=`

            <th class="cart_item_heading">${column}

            </th>

            `




        })


        list.forEach(info => {
            
            order_enteries.innerHTML+=`
        
            <tr class="order_entry">

                <td>${info.created_at}</td>

                <td>${info.product_id} </td>


                

                <td><a href="/products/${info.product_id}" target="_blank">${info.name} </a>
                
                <td>${info.price}</td>

             

                <td>${info.quantity_purchased}</td>


                <td>${info.amount}</td>

                

            <tr>
            `;



        });



    }

    








const fetch_details = async() => {


    try{    const http_response = await fetch(`/api/orders/${order_id}`);

        if(http_response.ok){

            const json_response = await http_response.json()
        
            console.log(' json response is ',json_response.data);

            render_table(json_response.data)



        } 

    }catch(error) {

            console.log(error)


        }




}



fetch_details();

});
