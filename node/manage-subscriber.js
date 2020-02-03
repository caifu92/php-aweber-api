
const fetch = require("node-fetch");
const credentials = require("./credentials.json");
const BASE_URL = 'https://api.aweber.com/1.0/';
const client_id = credentials['clientId'];
const client_secret = credentials['clientSecret'];
const accessToken = credentials['accessToken'];

const qs = require("querystring");

/**
 * Get all of the entries for a collection
 *
 * @param string $accessToken Access token to pass in as an authorization header
 * @param string $url Full url to make the request
 * @return array Every entry in the collection
 */

async function getCollection(accessToken,url) {
    let res;
    const collection = [];
    console.log({accessToken})
    while(url) {
        res = await fetch(url,{
            headers:{
                "Authorization":`Bearer ${accessToken}`
            }
        });
        let page = await res.json();
        console.log("got page",{page})
        collection.push(...page.entries);
        url = page.next_collection_link;
    }
    return collection;
}

(async () => {
    // get all the accounts entries
    const accounts = await getCollection(accessToken, BASE_URL + 'accounts');
    console.log({accounts});
    const accountUrl = accounts[0]['self_link'];

    // get all the list entries for the first account
    const listsUrl = accounts[0]['lists_collection_link'];
    const lists = await getCollection( accessToken, listsUrl);

    // find out if a subscriber exists on the first list
    const email = 'example@example.com';
    let params = {
        'ws.op' : 'find',
        'email' : email
    }
    const subsUrl = lists[0]['subscribers_collection_link'];
    const findUrl = subsUrl + '?' + qs.stringify(params);
    const foundSubscribers = await getCollection(accessToken, findUrl);
    console.log ('Found subscribers: ');
    console.log(foundSubscribers);

    let subscriber;
    let subscriberUrl;
    let subscriberResponse;
    if (foundSubscribers[0]['self_link']) {
        // update the subscriber if they are on the first list
        const data = {
            'custom_fields' : {
                'awesomeness' : 'really awesome'
            },
            'tags' : {
                'add'  :['prospect']
            }
        };
        subscriberUrl = foundSubscribers[0]['self_link'];
         subscriberResponse = await fetch(subscriberUrl, {
            method:"patch",
            body:JSON.stringify(data),
            headers:{
                'Authorization' : 'Bearer ' + accessToken
            },
        });

         subscriber = await subscriberResponse.json (subscriberResponse);
        console.log ('Updated Subscriber: ');
    } else {
        // add the subscriber if they are not already on the first list
        const data = {
            'email' : email,
            'custom_fields' :{
                'awesomeness' : 'somewhat'
            },
            'tags'  :['prospect']
        };
        const body = await fetch(subsUrl, {
            method:"post",
            data:JSON.stringify(data),
            headers:{

                "Access-Control-Expose-Headers": "Location",
                'Authorization' : 'Bearer ' + accessToken
            }
        });

        // get the subscriber entry using the Location header from the post request
        subscriberUrl = body.header.get('Location');
         subscriberResponse = await fetch(subscriberUrl, {
            'headers' :{
                'Authorization' : 'Bearer ' + accessToken
            }
        });
         subscriber = await subscriberResponse.json();
        console.log( 'Created Subscriber: ');
    }
    console.log(subscriber);

    // get the activity for the subscriber
     params = {
        'ws.op' : 'getActivity'
    };
    const activityUrl = subscriberUrl + '?' + qs.stringify(params);
    const activity = await fetch(activityUrl, {
        'headers':{
            'Authorization' : 'Bearer ' + accessToken
        }

    });
    console.log('Subscriber Activity: ') ;
    console.log(await activity.json());

    // delete the subscriber; this can only be performed on confirmed subscribers
    // or a 405 Method Not Allowed will be returned
    if (subscriber['status'] === 'subscribed') {
        await fetch (subscriberUrl,{
            'headers' :{
                'Authorization'  :'Bearer ' + accessToken
            }
        });
        console.log('Deleted subscriber with email: ' + email) ;
    }
})();
