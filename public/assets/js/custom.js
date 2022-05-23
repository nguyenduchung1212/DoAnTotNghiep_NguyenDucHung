const btnBuy = document.getElementById('buy_product');

const btnSearchOrderStatus = document.getElementById('find_status_order');
const collapseMenu = document.getElementById('collapse_menu')
const collapseMenuBtn = document.getElementById('menu_collapse_btn')
const closeBtn = document.getElementById('close_collapse')


if (collapseMenuBtn) {
    collapseMenuBtn.addEventListener('click', () => {
        collapseMenu.style.visibility = 'visible';
    })
}

if (closeBtn) {
    closeBtn.addEventListener('click', () => {
        collapseMenu.style.visibility = 'hidden';
    })
}