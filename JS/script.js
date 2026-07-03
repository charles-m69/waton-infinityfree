// Toggle between card view and table view
const tableViewButton = document.getElementById('tableview');
const cardViewButton = document.getElementById('cardview');
const cardViewContainer = document.getElementById('prodcard');
const tableViewContainer = document.getElementById('prodtable');

const savedView = localStorage.getItem('productView');

function showCardView() {
    cardViewContainer.style.display = 'flex';
    tableViewContainer.style.display = 'none';
    cardViewButton.style.display = 'none';
    tableViewButton.style.display = 'flex';
    localStorage.setItem('productView', 'card');
}

function showTableView() {
    cardViewContainer.style.display = 'none';
    tableViewContainer.style.display = 'flex';
    cardViewButton.style.display = 'flex';
    tableViewButton.style.display = 'none';
    localStorage.setItem('productView', 'table');
}

if (savedView === 'table') {
    showTableView();
} else {
    showCardView();
}

cardViewButton.addEventListener('click', showCardView);

tableViewButton.addEventListener('click', showTableView);
