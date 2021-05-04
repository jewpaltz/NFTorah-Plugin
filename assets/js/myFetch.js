const API_ROOT = wpApiSettings.root + "NFTorah/v1/";

const Session = { };

function NFTorah_api(url, data, method){

    let promise;

    const headers = {
        authorization: `bearer ${Session.token}`,
        'X-WP-Nonce': wpApiSettings.nonce
    };

    if(data){
        promise = fetch(API_ROOT + url, {
            method: method ?? 'POST', // *GET, POST, PUT, DELETE, etc.
            cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
            headers: {
                ...headers,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data) // body data type must match "Content-Type" header
          });
    }else{
        promise = fetch(API_ROOT + url, { headers });

    }
    return promise
        .then(x=> {
            if(x.ok) return x.json();
            else return x.json().then(y=> { throw y; })
        })
        .catch(err=>{
            console.error(err);
            toastError(err.msg || err);
        });
}

function toastError(msg){
    toastr.open({
        message: msg,
        queue: false,
        type: 'is-danger'
    })
}