document.getElementById("login-form").addEventListener("submit", function (e) {
  e.preventDefault();

  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;

  fetch("http://localhost:8081", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ username, password }),
    credential: "include",
  })
    .then((response) => {
      console.log(response); // Affiche toute la réponse du serveur pour débogage
      return response.text(); // Utiliser text() pour éviter l'erreur JSON si c'est du HTML
    })
    .then((data) => {
      console.log(data);
      if (data.message = "connexion good") {
        window.location.href = "http://localhost:8080"; // Rediriger vers la page d'accueil
      } else {
        document.getElementById("error-message").textContent =
          "Identifiants incorrects";
      }
    })
    .catch((error) => {
      console.log(error);
      document.getElementById("error-message").textContent =
        "Erreur de connexion";
    });
});
