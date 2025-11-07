let currentCategory = "all";
let products = [];

function filterCategory(category, event) {
    currentCategory = category;

    let buttons = document.querySelectorAll(".category-btn");
    buttons.forEach((btn) => {
        btn.classList.remove("active");
        btn.classList.remove("btn-primary");
        btn.classList.add("btn-outline-primary");
    });
    event.classList.add("active");
    event.classList.remove("btn-outline-primary");
    event.classList.add("btn-primary");
    console.log({
        currentCategory: currentCategory,
        category: category,
        event: event,
    });

    renderProducts();
}

async function renderProducts(searchProduct = "") {
    const productGrid = document.getElementById("productGrid");
    productGrid.innerHTML = "";

    const response = await fetch("/get-products");
    products = await response.json(); //[{id: 1}]

    // filter
    const filtered = products.filter((p) => {
        // shorthand / ternery
        const matchCategory =
            currentCategory === "all" ||
            p.category.category_name === currentCategory;
        const matchSearch = p.product_name
            .toLowerCase()
            .includes(searchProduct);
        return matchCategory && matchSearch;
    });

    // munculin data dari table products
    filtered.forEach((product) => {
        const col = document.createElement("div");
        col.className = "col-md-4 col-sm-6";
        col.innerHTML = `<div class="card product-card" onclick="addToCart(${product.id})">
        <div class="product-img">
            <img src="/storage/${product.product_photo}" alt="" width="100%">
        </div>
        <div class="card-body">
            <span class="badge bg-secondary badge-category">${product.category.category_name}</span>
            <h6 class="card-title mt-2 mb-2">${product.product_name}</h6>
            <p class="card-text text-primary fw-bold">Rp. ${product.product_price}</p>
        </div>
      </div>`;
        productGrid.appendChild(col);
    });
}

let cart = [];
function addToCart(id) {
    const product = products.find((p) => p.id == id);
    // if (!product) {
    //   return;
    // }
    const existing = cart.find((item) => item.id == id);
    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push({ ...product, quantity: 1 });
    }
    renderCart();
}

function renderCart() {
    const cartContainer = document.querySelector("#cartItems");
    cartContainer.innerHTML = "";

    if (cart.length === 0) {
        cartContainer.innerHTML = `
                    <div class="cart-items" id="cartItems">
                    <div class="text-center text-muted mt-5">
                        <i class="bi bi-cart mb-3"></i>
                        <p>Cart Empty</p>
                    </div>
                </div>`;
        updateTotal();
        // return;
    }
    cart.forEach((item, index) => {
        const div = document.createElement("div");
        div.className =
            "cart-item d-flex justify-content-between align-items-center mb-2";
        div.innerHTML = `
                <div>
                    <img src='/storage/${
                        item.product_photo
                    }' alt='tess' width='100'>
                    <strong class='mb-1'>${item.product_name}</strong>
                    <small class='text-muted'>Rp. ${item.product_price.toLocaleString(
                        "id-ID"
                    )}</small>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary me-2" onclick="changeQty(${
                        item.id
                    }, -1)">-</button>
                    <span>${item.quantity}</span>
                    <button class="btn btn-outline-secondary ms-3" onclick="changeQty(${
                        item.id
                    }, 1)">+</button>
                    <button class="btn btn-sm btn-danger ms-3" onclick="removeItem(${
                        item.id
                    })">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>`;
        cartContainer.appendChild(div);
    });
    updateTotal();
}
//hapus item dari cart
function removeItem(id) {
    cart = cart.filter((p) => p.id != id);
    renderCart();
}
//mengatur qty di cart
function changeQty(id, x) {
    const item = cart.find((p) => p.id == id);
    if (!item) {
        return;
    }
    item.quantity += x;
    if (item.quantity <= 0) {
        alert("minimum harus 1 product");
        item.quantity += 1;
        // cart = filter((p) => p.id != id);
    }
    renderCart();
}
function updateTotal() {
    const subtotal = cart.reduce(
        (sum, item) => sum + item.product_price * item.quantity,
        0
    );
    const tax = subtotal * 0.1;
    const total = tax + subtotal;

    document.getElementById(
        "subtotal"
    ).textContent = `Rp. ${subtotal.toLocaleString()}`;
    document.getElementById("tax").textContent = `Rp. ${tax.toLocaleString()}`;
    document.getElementById(
        "total"
    ).textContent = `Rp. ${total.toLocaleString()}`;

    document.getElementById("subtotal_value").value = subtotal;
    document.getElementById("tax_value").value = tax;
    document.getElementById("total_value").value = total;
    // 10.000.00
    // 10000
    // console.log(subtotal);
    // console.log(tax);
    // console.log(total);
}
document.getElementById("clearCart").addEventListener("click", function () {
    cart = [];
    renderCart();
});

async function processPayment() {
    if (cart.length === 0) {
        alert("Cart Masih Kosong");
        return;
    }

    const modal = new bootstrap.Modal(document.getElementById("exampleModal"));
    modal.show();
}

async function handlePayment() {
    const paymentMethod = document.getElementById("payment_method").value;
    const order_code = document
        .querySelector(".orderNumber")
        .textContent.trim();
    const subtotal = document.querySelector("#subtotal_value").value.trim();
    const tax = document.querySelector("#tax_value").value.trim();
    const grandTotal = document.querySelector("#total_value").value.trim();

    if (paymentMethod == "cash") {
        try {
            const res = await fetch("/order", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify({
                    cart,
                    order_code,
                    subtotal,
                    tax,
                    grandTotal,
                }),
            });
            const data = await res.json();
            if (data.status == "success") {
                alert("Transaction success");
                window.location.href = "order";
            } else {
                alert("Transaction failed", data.message);
            }
        } catch (error) {
            alert("Upss transaction fail");
            console.log("error", error);
        }
    } else if (paymentMethod == "cashless") {
        try {
            const res = await fetch("/cashless", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify({
                    cart,
                    order_code,
                    subtotal,
                    tax,
                    grandTotal,
                }),
            });
            const data = await res.json();
            if (data.status == "success") {
                window.snap.pay(data.snapToken);
            } else {
                alert("Transaction failed", data.message);
            }
        } catch (error) {
            alert("Upss transaction fail");
            console.log("error", error);
        }
    }
}

// useEffect(() => {
// }, [])

// DomContentLoaded : akan meload function pertama kali
renderProducts();
document
    .getElementById("searchProduct")
    .addEventListener("input", function (e) {
        const searchProduct = e.target.value.toLowerCase();
        renderProducts(searchProduct);
    });
