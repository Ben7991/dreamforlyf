const addToCartBtns = document.querySelectorAll(".btn-cart");
const cartItemCountElement = document.querySelector("#cart-count");
const allowedTotalItems = document.querySelector("#totalQuantity").value;
const spinner = document.querySelector("#spinner");
let totalCartItems = 0;

const emptyCartElement = document.querySelector("#no-item-description");
const cartDetailsElement = document.querySelector("#cart-details");

let store = [];

if (localStorage.getItem("cartItems")) {
    let storedItems = JSON.parse(localStorage.getItem("cartItems"));
    store.push(...storedItems);
    cartItemCountElement.textContent = store.length;

    storedItems.forEach((item) => createCartItem(item, item.quantity));
    totalCartItems = storedItems.length;
    updateTotalQuantity();
}

function populateStore(data) {
    const itemIndex = store.findIndex((item) => item.id === data.id);

    if (itemIndex === -1) {
        const item = {
            id: data.id,
            quantity: 1,
            name: data.name,
            image: data.image,
        };
        store.push(item);
    } else {
        store[itemIndex].quantity++;
        itemExist = true;
    }

    localStorage.setItem("cartItems", JSON.stringify(store));
    totalCartItems = store.length;
    cartItemCountElement.textContent = totalCartItems.toString();

    updateTotalQuantity();
}

function reCreateCartItems() {
    const cartItemHolders = document.querySelectorAll(".cart-item");
    cartItemHolders.forEach((element) => element.remove());
    store.forEach((item) => createCartItem(item, item.quantity));
}

function updateTotalQuantity() {
    const totalQuantityCountElement = document.querySelector("#total-quantity");
    let totalQuantity = 0;

    for (let item of store) {
        totalQuantity += item.quantity;
    }

    totalQuantityCountElement.textContent = totalQuantity.toString();
}

function checkIfItemExist(data) {
    const itemIndex = store.findIndex((item) => item.id === data.id);
    return itemIndex > -1;
}

function isMaximumQuantityReached() {
    let totalQuantity = 0;

    for (let item of store) {
        totalQuantity += item.quantity;
    }

    return totalQuantity === +allowedTotalItems;
}

function changeQuantity(productId, action) {
    const itemIndex = store.findIndex((item) => item.id === +productId);

    if (action === "MINUS") {
        if (store[itemIndex].quantity === 1) {
            return 1;
        }
        store[itemIndex].quantity--;
    } else if (action === "PLUS") {
        store[itemIndex].quantity++;
    }

    localStorage.setItem("cartItems", JSON.stringify(store));
    return store[itemIndex].quantity;
}

function findParentElement(accessKey) {
    const itemsElement = document.getElementsByClassName("cart-item");
    let element = null;

    for (let i = 0; i < itemsElement.length; i++) {
        if (itemsElement[i].accessKey === accessKey) {
            element = itemsElement[i];
            break;
        }
    }

    return element;
}

function removeCartItem(productId, parentAccessKey) {
    findParentElement(parentAccessKey).remove();

    let existingItemIndex = store.findIndex((item) => item.id === +productId);
    store.splice(existingItemIndex, 1);
    localStorage.setItem("cartItems", JSON.stringify(store));

    totalCartItems = store.length;
    cartItemCountElement.textContent = totalCartItems.toString();

    if (store.length === 0) {
        emptyCartElement.classList.remove("d-none");
        cartDetailsElement.classList.add("d-none");
    }

    updateTotalQuantity();
}

function getInputElement(accessKey) {
    let element = findParentElement(accessKey);
    let inputElement = element.children[2].children[1];
    return inputElement;
}

function increaseItemQuantity(productId, parentAccessKey) {
    let isMaximumReached = isMaximumQuantityReached();

    if (isMaximumReached) {
        return;
    }

    let inputElement = getInputElement(parentAccessKey);
    let quantity = changeQuantity(productId, "PLUS");
    inputElement.value = quantity.toString();

    updateTotalQuantity();
}

function decreaseItemQuantity(productId, parentAccessKey) {
    let inputElement = getInputElement(parentAccessKey);
    let quantity = changeQuantity(productId, "MINUS");
    inputElement.value = quantity.toString();
    updateTotalQuantity();
}

function createCartItem(data, quantity = 1) {
    if (!emptyCartElement.classList.contains("d-none")) {
        emptyCartElement.classList.add("d-none");
    }

    if (cartDetailsElement.classList.contains("d-none")) {
        cartDetailsElement.classList.remove("d-none");
    }

    const wrapper = document.createElement("div");
    wrapper.accessKey = `product-${data.id}`;
    wrapper.className = "cart-item mb-3 pb-2 border-bottom";
    wrapper.innerHTML = `
        <img src="${data.image}" class="cart-item-img">
        <p class="m-0 text-secondary">${data.name}</p>
        <div class="cart-item-action">
            <button class="action-btn text-secondary" onclick="decreaseItemQuantity('${data.id}', '${wrapper.accessKey}')">
                <i class="bi bi-dash"></i>
            </button>
            <input type="text" class="cart-item-input text-center" value="${quantity}" readonly id="${data.name}">
            <button class="action-btn text-secondary" onclick="increaseItemQuantity('${data.id}', '${wrapper.accessKey}')">
                <i class="bi bi-plus"></i>
            </button>
        </div>
        <button class="btn btn-danger cart-remove-btn" onclick="removeCartItem('${data.id}', '${wrapper.accessKey}')">
            <i class="bi bi-trash"></i>
        </button>
    `;

    cartDetailsElement.firstElementChild.appendChild(wrapper);
}

function addCartItem(productId) {
    let isMaximumReached = isMaximumQuantityReached();

    if (isMaximumReached) {
        showMessage("Maximum quantity is reached", "danger");
        return;
    }

    spinner.classList.remove("d-none");

    $.ajax({
        url: `/products/${productId}`,
        method: "GET",
        success: function (data, xhr, status) {
            spinner.classList.add("d-none");

            let itemExist = checkIfItemExist(data.data);

            if (itemExist) {
                populateStore(data.data);
                reCreateCartItems();
                return;
            }

            populateStore(data.data);
            createCartItem(data.data);
        },
        error: function (xhr, status, error) {
            console.log(xhr);
            spinner.classList.add("d-none");
        },
    });
}

function remainingQuantity() {
    let totalQuantity = 0;

    for (let item of store) {
        totalQuantity += item.quantity;
    }

    return totalQuantity;
}

function completeOrder() {
    let isReached = isMaximumQuantityReached();
    let totalQuantity = remainingQuantity();

    if (!isReached) {
        showMessage(
            `Please add ${
                +allowedTotalItems - totalQuantity
            } more products to complete`,
            "danger"
        );
        return;
    }

    let formData = {
        packageId: document.querySelector("#packageId").value,
        products: store.map((item) => {
            return { id: item.id, quantity: item.quantity };
        }),
    };

    const locale = document.querySelector("#locale").value;
    spinner.classList.remove("d-none");

    $.ajax({
        url: "/maint-packages",
        data: formData,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector("#token").value,
        },
        success: function (data, xhr, status) {
            localStorage.removeItem("cartItems");
            showMessage(data.message, "success");
            setTimeout(() => {
                spinner.classList.add("d-none");
                window.location.href = `/${locale}/distributor/order-history`;
            }, 2000);
        },
        error: function (xhr, status, error) {
            console.log(xhr);
            spinner.classList.add("d-none");
            showMessage(xhr.responseJSON.message, "danger");
        },
    });
}
