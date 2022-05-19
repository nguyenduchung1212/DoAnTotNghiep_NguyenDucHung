const btnBuy = document.getElementById('buy_product');

const btnSearchOrderStatus = document.getElementById('find_status_order');
const orderStatus = document.getElementById('order_status')


function moveToCart() {
    location.href = "cart.html";
}


// if (btnBuy) {
//     btnBuy.addEventListener('click', () => {
//         location.href = "cart.html";
//     });
// }


if (btnSearchOrderStatus) {
    btnSearchOrderStatus.addEventListener('click', () => {
        orderStatus.style.visibility = 'visible';
    })
}