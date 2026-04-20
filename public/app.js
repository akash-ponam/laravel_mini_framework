
console.log('script is loading');

document.addEventListener("DOMContentLoaded",()=>{






console.log("js loaded");

const reg_form = document.getElementById('reg_form');


const login_form = document.getElementById('login_form');



const login_view = document.getElementById('login_view');

const register_view = document.getElementById('register_view');

const reg_to_login_btn  = document.getElementById('reg_to_login');

const login_to_reg_btn = document.getElementById('login_to_reg');

const submit_button  = document.getElementById('submit_button');

const btn_text = document.getElementById('btn_text');

const btn_spinner  =  document.getElementById('btn_spinner');

const profile_view = document.getElementById('profile_view');

const card_title = document.getElementById('title');

const login_btn_spinner =  document.getElementById('login_btn_spinner');

const login_btn_text = document.getElementById('login_btn_text');


const btn_home = document.getElementById('btn_home');





    function reset_ui() {
        
        profile_view.style.display='none';
        btn_text.style.display = 'block';
        btn_spinner.style.display = 'none';
        submit_button.disabled = false;
        card_title.style.display='block';
        register_view.style.display='block';
        login_view.style.display='none';
    }




    function show_profile(){

    profile_view.style.display='block';
    card_title.style.display='none';
    register_view.style.display='none';
    login_view.style.display='none';

    }

    
    function show_profile_existing_user() {
    
    profile_view.style.display='block';
    card_title.style.display='none';
    register_view.style.display='none';
    login_view.style.display='none';

    document.getElementById('user_email').innerText = localStorage.getItem('auth_mail');

    document.getElementById('usr_img').src = localStorage.getItem('auth_profile_url');



    }



const switch_to_login = (e) => {

    console.log('console');
    e.preventDefault();
    register_view.style.display='none';
    login_view.style.display='block';


}


const switch_to_register = (e) => {


    e.preventDefault();
    register_view.style.display='block';
    login_view.style.display='none';





}


const validate_session = async() => {


    const session_response_http = await fetch('/api/session');

    if(session_response_http.ok){

        const session_response_json = await session_response_http.json();

        console.log("SESSION VALUE IS ",session_response_json);
    
        if(session_response_json.status==="success"){

        show_profile_existing_user();


        }





    }




}





const logout = async(e) => {

    


    e.preventDefault();


    const  logout_res_http =  await fetch('/api/logout');

    if(logout_res_http.ok){


       const  logout_res_json =  await logout_res_http.json();

        console.log(' logout response is ',logout_res_json);

        if(logout_res_json.status ==='success'){
            
            console.log('logout ho gya lol');

            localStorage.removeItem('auth_profile_url');

            localStorage.removeItem('auth_mail');

            localStorage.removeItem('auth_loggedin');

            reset_ui();



        }


    }





}


validate_session();

btn_home.addEventListener("click",logout);



reg_to_login_btn.addEventListener("click",switch_to_login);

login_to_reg_btn.addEventListener("click",switch_to_register);




const handle_login = async (e)  => {


login_btn_spinner.style.display='block';

login_btn_text.style.display='none';




e.preventDefault();

const login_form_data = new FormData(login_form);

try {


    const response_http = await fetch('/api/login',{method:'POST',body:login_form_data});

    const response_json = await response_http.json();

    console.log('login response is ',response_json);



        if(response_http.ok){

            console.log(' user registeration complete !!!!');

            localStorage.setItem("auth_loggedin",true);
            
            localStorage.setItem('auth_mail',response_json.data.email);

            localStorage.setItem('auth_profile_url',response_json.data.profile_url);


            setTimeout(()=>{


                show_profile();

                document.getElementById('user_email').innerText = response_json.data.email;

                document.getElementById('usr_img').src = response_json.data.profile_url;

            },800);





        }else{
            console.log(" no such field exist");
            reset_ui();
        }





    




    
} catch (error) {

    
    login_btn_spinner.style.display='none';

    login_btn_text.style.display='block';

    reset_ui();


}
}


const handle_submit = async (e) =>{

    e.preventDefault();

    btn_text.style.display='none';
    btn_spinner.style.display='block';
    submit_button.disabled = true;


    const form_data = new FormData(reg_form);


    try {

    
        const response_http = await fetch('/api/register',{method:'POST',body:form_data});


        const response_json = await response_http.json();


        console.log(" json formarted response is ",response_json);
        
 
        if(response_http.ok){

            console.log(' user registeration complete !!!!');
         

            setTimeout(()=>{


                show_profile();

                document.getElementById('user_email').innerText = response_json.data.email;

                document.getElementById('usr_img').src = response_json.data.profile_url;

            },800);





        }else{
            console.log(" no such field exist");
            reset_ui();
        }

        

        
    } catch ({name,message,stack}) {
        
    
        
        console.log(stack);
        reset_ui();

    }

  



    }









reg_form.addEventListener('submit',handle_submit);

login_form.addEventListener('submit',handle_login);















})
