document.addEventListener("DOMContentLoaded",()=>{


    const segments = window.location.pathname.split('/');

    const product_id = segments.pop() || segments.pop();


    console.log("yay script loaded");

    const container = document.querySelector('.product_detail');



    const render_product = (root,product) =>{

        const product_img = document.createElement('img');

        const product_title = document.createElement('h1');

        const price = document.createElement('button');

        price.classList.add("product_price");

        price.innerText= product.price;

        product_title.classList.add("product_title");

        product_title.innerText=product.name;

        product_img.classList.add("product_img");

        product_img.src=product.image_url;

        root.appendChild(product_img);

        root.appendChild(product_title);

        root.appendChild(price);



    }



    const fetch_product_detail = async () => {


        try {


        const response_http = await fetch(`/api/products/${product_id}`);

        if(response_http.ok) {


            const response_json = await response_http.json();

            container.classList.add('loaded');

            

            const product = response_json.data.data; 

            render_product(container,product);

            console.log('product is ',product);
            



        }
            

        } catch (error) {
            console.log(error);
        }
        







    }







    fetch_product_detail();

})
