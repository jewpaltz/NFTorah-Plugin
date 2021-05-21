// A reference to Stripe.js
const stripe = Stripe(wpApiSettings.STRIPE_PUB_KEY);
/* global stripe; */
//debugger;

var orderData = {
  items: [{ id: "torah-letter" }],
  currency: "usd"
};

function setupStripeElements() {
    var elements = stripe.elements();
    var style = {
        base: {
            color: "#32325d",
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: "antialiased",
            fontSize: "16px",
            "::placeholder": {
                color: "#aab7c4"
            }
        },
        invalid: {
            color: "#fa755a",
            iconColor: "#fa755a"
        }
    };

    var card = elements.create("card", { style: style });
    card.mount("#card-element");

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function(event) {
        if (event.error) {
            toastError(event.error.message ?? event.error);
        }
    });

    return card;
};

var handleAction = function(clientSecret) {
  stripe.handleCardAction(clientSecret).then(function(data) {
    if (data.error) {
      throw Error("Your card was not authenticated, please try again");
    } else if (data.paymentIntent.status === "requires_confirmation") {
      fetch("/pay.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          paymentIntentId: data.paymentIntent.id
        })
      })
        .then(function(result) {
          return result.json();
        })
        .then(function(json) {
          if (json.error) {
              throw json.error;
          } else {
            orderComplete(clientSecret);
          }
        });
    }
  });
};

/*
 * Collect card details and pays for the order
 */
function pay_stripe(stripe, card) {
  return stripe
    .createPaymentMethod("card", card)
    .then(function(result) {
      if (result.error) {
          throw result.error;
      } else {
          return result;
      }
    });
}
 
function handlePaymentAction(paymentData) {
      if (paymentData.requiresAction) {
        // Request authentication
        handleAction(paymentData.clientSecret);
      } else if (paymentData.error) {
        throw paymentData.error;
      } else {
        orderComplete(paymentData.clientSecret);
      }
};

/* ------- Post-payment helpers ------- */

/* Shows a success / error message when the payment is complete */
var orderComplete = function(clientSecret) {
  stripe.retrievePaymentIntent(clientSecret).then(function(result) {
    var paymentIntent = result.paymentIntent;
    console.log(paymentIntent);
  });
};
