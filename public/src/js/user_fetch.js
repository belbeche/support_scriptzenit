const btnRegister = document.querySelector('#btn-register');

const devisEmail = document.querySelector('#devis_email');
const devisTypeWeb = document.querySelector('#devis_typeDeSiteWeb');
const devisAttentesDesign = document.querySelector('#devis_attentes_design_web');
const devisDescription = document.querySelector('#devis_description_projet');
const devisUserPassword = document.querySelector('#user_password_password');


devis_type_de_site_web

btnRegister.addEventListener('click', function(e) {
    e.preventDefault()

    console.log(userCivility0.checked)
    console.log(userCivility1.checked)
    let userCivility = null;

    if (userCivility0.checked === true){
        userCivility = 'M.'
    } else if (userCivility1.checked === true){
        userCivility = 'Mme'
    } else {
        userCivility = null
    }

    const formData = {
        userLastName: userLastName.value,
        userFirstName: userFirstName.value,
        userEmail: userEmail.value,
        userCivility: userCivility,
        userFirstPassword: userFirstPassword.value,
        userSecondPassword: userSecondPassword.value,
        userChecked: userChecked.value
    }

    axios.post(btnRegister.dataset.registerUrl, formData)
        .then(response => {
            // envoie à la base de données du site support de scriptzenit
            const url2 = document.querySelector('.register-url2')
            // console.log(url2)
            // console.log(url2.dataset.registerUrl2)
            axios.post(url2.dataset.registerUrl2, formData)
                .then(response => {
                    location.href=url2.dataset.redirectToLogin
                })
                .catch(error => console.log(error))
        })
        .catch(error => console.log(error))
})
