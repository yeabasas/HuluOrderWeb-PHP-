const data = [
    // Sample data
    { id: 1, imageUrl: 'https://via.placeholder.com/150', ItemName: 'Item 1', Price: '$10', Brand: 'Brand A', Condition: 'New', Verified: true },
    { id: 2, imageUrl: 'https://via.placeholder.com/150', ItemName: 'Item 2', Price: '$20', Brand: 'Brand B', Condition: 'Used', Verified: false },
    { id: 3, imageUrl: 'https://via.placeholder.com/150', ItemName: 'Item 3', Price: '$30', Brand: 'Brand C', Condition: 'New', Verified: true },
    { id: 4, imageUrl: 'https://via.placeholder.com/150', ItemName: 'Item 4', Price: '$40', Brand: 'Brand D', Condition: 'Used', Verified: false },
    { id: 5, imageUrl: 'https://via.placeholder.com/150', ItemName: 'Item 5', Price: '$50', Brand: 'Brand E', Condition: 'New', Verified: true },
    { id: 5, imageUrl: 'https://via.placeholder.com/150', ItemName: 'Item 5', Price: '$50', Brand: 'Brand E', Condition: 'New', Verified: true },
    { id: 5, imageUrl: 'https://via.placeholder.com/150', ItemName: 'Item 5', Price: '$50', Brand: 'Brand E', Condition: 'New', Verified: true },
    { id: 5, imageUrl: 'https://via.placeholder.com/150', ItemName: 'Item 5', Price: '$50', Brand: 'Brand E', Condition: 'New', Verified: true },
    { id: 5, imageUrl: 'https://via.placeholder.com/150', ItemName: 'Item 5', Price: '$50', Brand: 'Brand E', Condition: 'New', Verified: true },
    { id: 5, imageUrl: 'https://via.placeholder.com/150', ItemName: 'Item 5', Price: '$50', Brand: 'Brand E', Condition: 'New', Verified: true },
    // Add more items as needed
];

const columns = [
    document.getElementById('left-column'),
    document.createElement('div'),
    document.createElement('div'),
    document.createElement('div'),
    document.getElementById('right-column')
];

// Add the new columns to the container
columns.slice(1, 4).forEach(column => {
    column.className = 'column';
    document.querySelector('.container').insertBefore(column, document.getElementById('right-column'));
});

const splitDataIntoColumns = (data, numColumns) => {
    const columnData = Array.from({ length: numColumns }, () => []);
    data.forEach((item, index) => {
        columnData[index % numColumns].push(item);
    });
    return columnData;
};

const columnData = splitDataIntoColumns(data, 5);

columnData.forEach((columnItems, columnIndex) => {
    columnItems.forEach(item => {
        const itemElement = createItemElement(item);
        columns[columnIndex].appendChild(itemElement);
    });
});

function createItemElement(item) {
    const itemElement = document.createElement('div');
    itemElement.className = 'item';
    itemElement.innerHTML = `
        <img src="${item.imageUrl}" alt="${item.ItemName}">
        <h3>${item.ItemName}</h3>
        <p>Price: ${item.Price}</p>
        <p>Brand: ${item.Brand}</p>
        <p>Condition: ${item.Condition}</p>
        <p>Verified: ${item.Verified}</p>
    `;
    return itemElement;
}
