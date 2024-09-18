const apiUrl = 'http://localhost/API_MVC/api.php';

async function getAllUsers() {
    try {
        const response = await fetch(apiUrl);
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! Status: ${response.status}, Response: ${errorText}`);
        }
        const users = await response.json();
        document.getElementById('all-users').innerText = JSON.stringify(users, null, 2);
    } catch (error) {
        console.error('Error fetching users:', error);
    }
}

async function getUserById() {
    const userId = document.getElementById('get-user-id').value;
    if (!userId) {
        alert('Please enter a User ID');
        return;
    }
    try {
        const response = await fetch(`${apiUrl}/${userId}`);
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! Status: ${response.status}, Response: ${errorText}`);
        }
        const user = await response.json();
        document.getElementById('user-details').innerText = JSON.stringify(user, null, 2);
    } catch (error) {
        console.error('Error fetching user:', error);
    }
}

async function createUser() {
    const name = document.getElementById('create-user-name').value;
    const email = document.getElementById('create-user-email').value;
    if (!name || !email) {
        alert('Please enter both name and email');
        return;
    }
    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, email })
        });
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! Status: ${response.status}, Response: ${errorText}`);
        }
        const result = await response.json();
        alert(`User created with ID: ${result.id}. New Data: ${JSON.stringify(result.new_data)}`);
    } catch (error) {
        console.error('Error creating user:', error);
    }
}

async function updateUser() {
    const userId = document.getElementById('update-user-id').value;
    const name = document.getElementById('update-user-name').value;
    const email = document.getElementById('update-user-email').value;
    if (!userId || !name || !email) {
        alert('Please enter User ID, name, and email');
        return;
    }
    try {
        const response = await fetch(`${apiUrl}/${userId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, email })
        });
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! Status: ${response.status}, Response: ${errorText}`);
        }
        const result = await response.json();
        alert(`User updated: ${result.modified} row(s) affected. Modified Data: ${JSON.stringify(result.modified_data)}`);
    } catch (error) {
        console.error('Error updating user:', error);
    }
}

async function deleteUser() {
    const userId = document.getElementById('delete-user-id').value;
    if (!userId) {
        alert('Please enter a User ID');
        return;
    }
    try {
        const response = await fetch(`${apiUrl}/${userId}`, {
            method: 'DELETE'
        });
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! Status: ${response.status}, Response: ${errorText}`);
        }
        const result = await response.json();
        alert(`User deleted: ${result.deleted} row(s) affected`);
    } catch (error) {
        console.error('Error deleting user:', error);
    }
}
