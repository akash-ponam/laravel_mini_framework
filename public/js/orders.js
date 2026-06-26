document.addEventListener("DOMContentLoaded",()=>{


    console.log('script load successs oh yea')

    order_heading_row = document.querySelector(".order_heading_row");

    order_enteries = document.querySelector(".order_enteries");





const render_table = (list) =>{

        order_heading_row.innerHTML='';

        order_enteries.innerHTML=''

         columns = [... new Set(list.flatMap(obj=>Object.keys(obj)))];

        console.log('columns are ',columns);

        columns.forEach(column =>{


            order_heading_row.innerHTML+=`

            <th class="cart_item_heading">${column}

            </th>

            `




        })


        list.forEach(order => {
            
            order_enteries.innerHTML+=`
        
            <tr class="order_entry">

                <td>${order.created_at}</td>

                <td><a href="/my_orders/${order.order_id}">${order.order_id} </a>
                
            
                <td>${order.amount}</td>

            <tr>
            `;



        });



    }





    const fetch_orders = async () =>{


                try {


                    const http_response = await fetch('/api/orders');

                    if(http_response.ok){

                        
                        const json_response  = await http_response.json()

                        const orders = json_response.data;

                        console.log('orders are ',orders[0]);

                        render_table(orders)

                        
                    

            


                    }









        
    } catch (error) {
        
                console.log(error)
        
    }







    }





fetch_orders();


});
