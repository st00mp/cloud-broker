document.addEventListener('DOMContentLoaded', () => {
    fetch('/api/gpu/offers')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la récupération des données');
            }
            return response.json();
        })
        .then(data => {
            const tbody = document.querySelector('#offers-table tbody');
            data.forEach(offer => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${offer.id}</td>
                    <td>${offer.provider}</td>
                    <td>${offer.instanceType}</td>
                    <td>${offer.gpuModel}</td>
                    <td>${offer.vram}</td>
                    <td>${offer.vcpu}</td>
                    <td>${offer.price}</td>
                    <td>${offer.availabilityZone}</td>
                    <td>${offer.os_supported}</td>
                    <td>${offer.date}</td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error(error);
            document.body.insertAdjacentHTML('beforeend', `<p style="color:red;">${error}</p>`);
        });
});
