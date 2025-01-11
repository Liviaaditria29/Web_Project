document.addEventListener("DOMContentLoaded", function () {
  const distanceBtn = document.getElementById("distance-btn");
  const ratingBtn = document.getElementById("rating-btn");
  const promoBtn = document.getElementById("promo-btn");
  const infoBox = document.getElementById("info-box");
  const navBar = document.querySelector(".nav-bar");
  const body = document.body;
  const mapContainer = document.getElementById("map");

  function resizeMap() {
    const navbarHeight = navBar.offsetHeight;
    mapContainer.style.height = window.innerHeight - navbarHeight + "px";
  }

  resizeMap();
  window.addEventListener("resize", resizeMap);

  let userLat, userLng;

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(successLocation, errorLocation, {
      enableHighAccuracy: true, // Untuk meningkatkan akurasi
    });
  } else {
    alert("Geolokasi tidak didukung oleh browser Anda.");
  }

  function successLocation(position) {
    userLat = position.coords.latitude;
    userLng = position.coords.longitude;

    // Debugging koordinat pengguna
    console.log("Koordinat pengguna:", userLat, userLng);

    const map = L.map("map").setView([userLat, userLng], 14);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    L.marker([userLat, userLng])
      .addTo(map)
      .bindPopup("Anda di sini")
      .openPopup();

    const restaurantIcon = L.icon({
      iconUrl: "assets/images/icons/restaurant-icon.png",
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41],
    });

    // Data restoran dari PHP
    restaurantsData.forEach((restaurant) => {
      const distance = calculateDistance(
        userLat,
        userLng,
        restaurant.lat,
        restaurant.lng
      );

      L.marker([restaurant.lat, restaurant.lng], { icon: restaurantIcon })
        .addTo(map)
        .bindPopup(
          `<b>${restaurant.name}</b><br>Jarak: ${distance.toFixed(
            2
          )} km<br>Rating: ${
            restaurant.rating !== null ? restaurant.rating : "Tidak ada rating"
          }<br>Promo: ${restaurant.promo}
                  <br><button class="map-button" onclick="openGoogleMaps(${
                    restaurant.lat
                  }, ${restaurant.lng})">Dapatkan Petunjuk Arah</button>`
        );
    });
  }

  function errorLocation() {
    alert("Tidak dapat mengambil lokasi Anda.");
  }

  function calculateDistance(lat1, lng1, lat2, lng2) {
    const R = 6371; // Radius bumi dalam kilometer
    const dLat = ((lat2 - lat1) * Math.PI) / 180;
    const dLng = ((lng2 - lng1) * Math.PI) / 180;
    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos((lat1 * Math.PI) / 180) *
        Math.cos((lat2 * Math.PI) / 180) *
        Math.sin(dLng / 2) *
        Math.sin(dLng / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
  }

  window.openGoogleMaps = function (lat, lng) {
    const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
    window.open(googleMapsUrl, "_blank");
  };

  distanceBtn.addEventListener("click", () => {
    body.classList.toggle("show-info");
    infoBox.innerHTML = `<h3>Restoran Terdekat:</h3>`;

    if (restaurantsData.length > 0) {
      const distances = restaurantsData.map((restaurant) => {
        const distance = calculateDistance(
          userLat,
          userLng,
          restaurant.lat,
          restaurant.lng
        );
        return {
          name: restaurant.name,
          distance: distance,
        };
      });

      distances.sort((a, b) => a.distance - b.distance);

      const distanceList = distances
        .map((item) => {
          return `<li>${item.name}: ${item.distance.toFixed(2)} km.</li>`;
        })
        .join("");

      infoBox.innerHTML += `<ul>${distanceList}</ul>`;
    } else {
      infoBox.innerHTML += "<p>Data restoran tidak ditemukan.</p>";
    }
  });

  ratingBtn.addEventListener("click", () => {
    body.classList.toggle("show-info");
    infoBox.innerHTML = "<h3>Rating Restoran:</h3>";

    if (restaurantsData.length > 0) {
      const ratings = restaurantsData
        .map((restaurant) => {
          return `<li>${restaurant.name}: ${
            restaurant.rating !== null ? restaurant.rating : "Tidak ada rating"
          }</li>`;
        })
        .join("");
      infoBox.innerHTML += `<ul>${ratings}</ul>`;
    } else {
      infoBox.innerHTML += "<p>Data rating tidak ditemukan.</p>";
    }
  });

  promoBtn.addEventListener("click", () => {
    body.classList.toggle("show-info");
    infoBox.innerHTML = "<h3>Promo Restoran:</h3>";

    if (restaurantsData.length > 0) {
      const promos = restaurantsData
        .map((restaurant) => {
          return `<li>${restaurant.name} - Promo: ${restaurant.promo}</li>`;
        })
        .join("");
      infoBox.innerHTML += `<ul>${promos}</ul>`;
    } else {
      infoBox.innerHTML += "<p>Data promo tidak ditemukan.</p>";
    }
  });
});
