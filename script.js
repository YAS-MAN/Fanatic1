document.addEventListener("DOMContentLoaded", () => {
  // Variabel global 'cars' dan 'quizQuestions' sekarang didefinisikan di PHP
  // (dari classification.php, comparison.php, dan quiz.php)

  const carList = document.getElementById("car-list");
  const filterContainer = document.querySelector(".filter-buttons");
  const car1 = document.getElementById("car1");
  const car2 = document.getElementById("car2");
  const comparison = document.getElementById("comparison-result");
  const detailContainer = document.getElementById("car-detail");

  // === VARIABLES FOR QUIZ ===
  const startScreen = document.getElementById("start-screen");
  const quizInterface = document.getElementById("quiz-interface");
  const startBtn = document.getElementById("start-btn");
  const timerDisplay = document.getElementById("quiz-timer");
  const quizQuestion = document.getElementById("quiz-question");
  const quizOptions = document.getElementById("quiz-options");
  const nextBtn = document.getElementById("next-btn");
  const scoreText = document.getElementById("quiz-score");
  const progressText = document.getElementById("quiz-progress");

  // --- UTILS ---
  const getNumericValue = (str) => {
    const match = str.match(/\d+/);
    return match ? parseInt(match[0]) : 0;
  };

  function compareCarStats(car1, car2) {
    const topSpeed1 = getNumericValue(car1.topSpeed);
    const topSpeed2 = getNumericValue(car2.topSpeed);
    const topSpeedDiff = `${Math.abs(topSpeed1 - topSpeed2)} km/h ${
      topSpeed1 > topSpeed2 ? "lebih cepat" : "lebih lambat"
    }`;

    const weight1 = getNumericValue(car1.weight);
    const weight2 = getNumericValue(car2.weight);
    const weightDiff = `${Math.abs(weight1 - weight2)} kg ${
      weight1 < weight2
        ? "lebih ringan"
        : weight1 > weight2
        ? "lebih berat"
        : "sama"
    }`;

    const raceWins1 = car1.race_wins || 0; // Menggunakan nama kolom DB: race_wins
    const raceWins2 = car2.race_wins || 0;
    const raceWinsDiff = `${Math.abs(raceWins1 - raceWins2)} kemenangan ${
      raceWins1 > raceWins2 ? "lebih banyak" : "lebih sedikit"
    }`;

    const podiums1 = car1.podiums || 0; // Menggunakan nama kolom DB: podiums
    const podiums2 = car2.podiums || 0;
    const podiumsDiff = `${Math.abs(podiums1 - podiums2)} podium ${
      podiums1 > podiums2 ? "lebih banyak" : "lebih sedikit"
    }`;

    const points1 = car1.points || 0; // Menggunakan nama kolom DB: points
    const points2 = car2.points || 0;
    const pointsDiff = `${Math.abs(points1 - points2)} poin ${
      points1 > points2 ? "lebih banyak" : "lebih sedikit"
    }`;

    // Nilai perbandingan
    const topSpeed =
      topSpeed1 === topSpeed2 ? 0 : topSpeed1 > topSpeed2 ? 5 : -5;
    const weight = weight1 === weight2 ? 0 : weight1 < weight2 ? 5 : -5;
    const raceWins =
      raceWins1 === raceWins2 ? 0 : raceWins1 > raceWins2 ? 5 : -5;
    const podiums = podiums1 === podiums2 ? 0 : podiums1 > podiums2 ? 5 : -5;
    const points = points1 === points2 ? 0 : points1 > points2 ? 5 : -5;

    return {
      topSpeed,
      topSpeedDiff,
      weight,
      weightDiff,
      raceWins,
      raceWinsDiff,
      podiums,
      podiumsDiff,
      points,
      pointsDiff,
    };
  }

  // ====== CLASSIFICATION (classification.php) ======
  if (carList && typeof cars !== "undefined") {
    // Pastikan cars sudah dimuat oleh PHP di classification.php
    if (cars.length > 0) {
      // generate filter buttons based on unique teams
      if (filterContainer) {
        // Ambil data dari variabel global cars
        const teams = Array.from(new Set(cars.map((c) => c.team)));
        teams.forEach((team) => {
          const btn = document.createElement("button");
          btn.className = "filter-btn";
          btn.dataset.team = team;
          btn.textContent = team;
          btn.addEventListener("click", () => applyFilter(team));
          filterContainer.appendChild(btn);
        });
        const allBtn = filterContainer.querySelector('[data-team="all"]');
        if (allBtn) allBtn.addEventListener("click", () => applyFilter("all"));
      }

      function renderCards(list) {
        carList.innerHTML = "";
        list.forEach((car) => {
          const card = document.createElement("div");
          card.className = "card clickable";
          card.innerHTML = `
            <img src="${car.images || car.imageList || ""}" alt="${car.name}">
            <div class="card-content">
              <h3>${car.name}</h3>
              <p class="team-name" style="color: #ff0000; font-weight: 500;">${
                car.team
              }</p>
              <div class="card-description">
                ${
                  car.description
                    ? car.description.substring(0, 80) + "..."
                    : "Tidak ada deskripsi"
                }
              </div>
              <hr class="card-divider">
              <p><b>Engine:</b> ${car.engine}</p>
              <p><b>Top Speed:</b> ${car.topSpeed}</p>
              <p><b>Power:</b> ${car.power}</p>
              <p><b>Pembalap:</b> ${car.main_drivers}</p>
            </div>`; // Menggunakan main_drivers (DB name)
          card.addEventListener("click", () => {
            const url = new URL("detail.php", window.location.href);
            url.searchParams.set("name", car.name);
            window.location.href = url.toString();
          });
          carList.appendChild(card);
        });
      }

      function applyFilter(team) {
        const buttons = filterContainer?.querySelectorAll(".filter-btn");
        buttons?.forEach((b) => b.classList.remove("active"));
        const activeBtn = filterContainer?.querySelector(
          `[data-team="${team}"]`
        );
        activeBtn?.classList.add("active");
        if (team === "all") return renderCards(cars);
        renderCards(cars.filter((c) => c.team === team));
      }

      renderCards(cars);
    }
  }

  // ====== COMPARISON (comparison.php) ======
  if (car1 && car2 && typeof cars !== "undefined") {
    // Pastikan cars sudah dimuat oleh PHP di comparison.php
    if (cars.length > 1) {
      cars.forEach((car) => {
        const opt1 = document.createElement("option");
        const opt2 = document.createElement("option");
        opt1.value = car.name;
        opt1.textContent = `${car.name} (${car.year})`;
        opt2.value = car.name;
        opt2.textContent = `${car.name} (${car.year})`;
        car1.appendChild(opt1);
        car2.appendChild(opt2);
      });

      function showComparison() {
        // Find car data using the name selected in the dropdowns
        const c1 = cars.find((c) => c.name === car1.value);
        const c2 = cars.find((c) => c.name === car2.value);

        if (c1 && c2) {
          const stats = compareCarStats(c1, c2);

          comparison.innerHTML = `
            <div class="compare-gallery">
              <div class="compare-card">
                <img src="${c1.image_detail || c1.imageDetail}" alt="${
            c1.name
          }">
                <div class="compare-overlay">
                  <h3>${c1.name}</h3>
                  <span>${c1.team}</span>
                </div>
              </div>
              <div class="compare-card">
                <img src="${c2.image_detail || c2.imageDetail}" alt="${
            c2.name
          }">
                <div class="compare-overlay">
                  <h3>${c2.name}</h3>
                  <span>${c2.team}</span>
                </div>
              </div>
            </div>
            <div class="swap-wrap"><button class="swap-btn" aria-label="Tukar">
              ↔
            </button></div>
            <table class="compare-table">
              <tr><td>${c1.year}</td><th>Tahun</th><td>${c2.year}</td></tr>
              <tr><td>${c1.engine}</td><th>Engine</th><td>${c2.engine}</td></tr>
              <tr><td>${c1.power}</td><th>Power</th><td>${c2.power}</td></tr>
              <tr><td>${c1.top_speed}</td><th>Top Speed</th><td>${
            c2.top_speed
          }</td></tr>
              <tr><td>${c1.weight}</td><th>Weight</th><td>${c2.weight}</td></tr>
              <tr><td>${c1.chassis}</td><th>Chassis</th><td>${
            c2.chassis
          }</td></tr>
              <tr><td>${c1.wheelbase}</td><th>Wheelbase</th><td>${
            c2.wheelbase
          }</td></tr>
              <tr><td>${c1.acceleration}</td><th>Acceleration</th><td>${
            c2.acceleration
          }</td></tr>
              <tr><td>${c1.main_drivers}</td><th>Pembalap Utama</th><td>${
            c2.main_drivers
          }</td></tr>
              <tr><td>${c1.championships}</td><th>Jumlah Kejuaraan</th><td>${
            c2.championships
          }</td></tr>
              <tr><td>${c1.aerodynamics}</td><th>Aerodinamika</th><td>${
            c2.aerodynamics
          }</td></tr>
              <tr><td>${c1.suspension}</td><th>Suspensi</th><td>${
            c2.suspension
          }</td></tr>
              <tr><td>${c1.brakes}</td><th>Sistem Rem</th><td>${
            c2.brakes
          }</td></tr>
              <tr><td>${c1.transmission}</td><th>Transmisi</th><td>${
            c2.transmission
          }</td></tr>
              <tr><td>${
                c1.race_wins || "N/A"
              }</td><th>Kemenangan Balapan</th><td>${
            c2.race_wins || "N/A"
          }</td></tr>
              <tr><td>${c1.podiums || "N/A"}</td><th>Podium</th><td>${
            c2.podiums || "N/A"
          }</td></tr>
              <tr><td>${c1.points || "N/A"}</td><th>Poin</th><td>${
            c2.points || "N/A"
          }</td></tr>
              <tr><td>${c1.capacity || "N/A"}</td><th>Kapasitas Mesin</th><td>${
            c2.capacity || "N/A"
          }</td></tr>
              <tr><td>${c1.rpm || "N/A"}</td><th>RPM</th><td>${
            c2.rpm || "N/A"
          }</td></tr>
              <tr><td>${c1.valves || "N/A"}</td><th>Katup</th><td>${
            c2.valves || "N/A"
          }</td></tr>
            </table>
            
            <div class="stats-comparison">
              <h2>Statistik Perbandingan</h2>
              <div class="stats-grid">
                <div class="stats-card ${
                  stats.topSpeed > 0
                    ? "advantage-left"
                    : stats.topSpeed < 0
                    ? "advantage-right"
                    : ""
                }">
                  <h3>Top Speed</h3>
                  <div class="stats-bar">
                    <div class="stats-indicator" style="left: ${
                      50 + stats.topSpeed * 5
                    }%"></div>
                  </div>
                  <div class="stats-labels">
                    <span>${c1.name}</span>
                    <span>${c2.name}</span>
                  </div>
                  <p class="stats-diff">${stats.topSpeedDiff}</p>
                </div>
                
                <div class="stats-card ${
                  stats.weight > 0
                    ? "advantage-right"
                    : stats.weight < 0
                    ? "advantage-left"
                    : ""
                }">
                  <h3>Weight</h3>
                  <div class="stats-bar">
                    <div class="stats-indicator" style="left: ${
                      50 + stats.weight * 5
                    }%"></div>
                  </div>
                  <div class="stats-labels">
                    <span>${c1.name}</span>
                    <span>${c2.name}</span>
                  </div>
                  <p class="stats-diff">${stats.weightDiff}</p>
                </div>
                
                <div class="stats-card ${
                  stats.raceWins > 0
                    ? "advantage-left"
                    : stats.raceWins < 0
                    ? "advantage-right"
                    : ""
                }">
                  <h3>Race Wins</h3>
                  <div class="stats-bar">
                    <div class="stats-indicator" style="left: ${
                      50 + stats.raceWins * 5
                    }%"></div>
                  </div>
                  <div class="stats-labels">
                    <span>${c1.name}</span>
                    <span>${c2.name}</span>
                  </div>
                  <p class="stats-diff">${stats.raceWinsDiff}</p>
                </div>
                
                <div class="stats-card ${
                  stats.podiums > 0
                    ? "advantage-left"
                    : stats.podiums < 0
                    ? "advantage-right"
                    : ""
                }">
                  <h3>Podiums</h3>
                  <div class="stats-bar">
                    <div class="stats-indicator" style="left: ${
                      50 + stats.podiums * 5
                    }%"></div>
                  </div>
                  <div class="stats-labels">
                    <span>${c1.name}</span>
                    <span>${c2.name}</span>
                  </div>
                  <p class="stats-diff">${stats.podiumsDiff}</p>
                </div>
                
                <div class="stats-card ${
                  stats.points > 0
                    ? "advantage-left"
                    : stats.points < 0
                    ? "advantage-right"
                    : ""
                }">
                  <h3>Points</h3>
                  <div class="stats-bar">
                    <div class="stats-indicator" style="left: ${
                      50 + stats.points * 5
                    }%"></div>
                  </div>
                  <div class="stats-labels">
                    <span>${c1.name}</span>
                    <span>${c2.name}</span>
                  </div>
                  <p class="stats-diff">${stats.pointsDiff}</p>
                </div>
              </div>
            </div>
          `;
          const swapBtn = comparison.querySelector(".swap-btn");
          swapBtn?.addEventListener("click", () => {
            const temp = car1.value;
            car1.value = car2.value;
            car2.value = temp;
            showComparison();
          });
        }
      }
      car1.addEventListener("change", showComparison);
      car2.addEventListener("change", showComparison);
      // set default selections and render initially
      car1.value = cars[0].name;
      car2.value = cars[1].name;
      showComparison();
    }
  }

  // ====== DETAIL PAGE (detail.php) ======
  if (detailContainer && typeof cars !== "undefined") {
    const params = new URLSearchParams(window.location.search);
    const name = params.get("name");

    // Karena detail.php menyuntikkan array 'cars' yang hanya berisi 1 mobil yang dicari
    const car = cars[0];

    if (car) {
      detailContainer.innerHTML = `
        <div class="detail-hero">
          <img src="${car.image_detail || car.imageDetail || ""}" alt="${
        car.name
      }">
          <div class="detail-hero-content">
            <h1>${car.name}</h1>
            <p class="team-name" style="color: #ff0000; font-weight: 500;">${
              car.team
            }</p>
            <div class="car-description">
              <p>${car.description}</p>
            </div>
          </div>
        </div>
        
        <div class="driver-section">
          <h2>Drivers</h2>
          <div class="driver-cards">
            <div class="driver-card">
              <img src="${car.driver1_image || car.driver1Image || ""}" alt="${
        car.driver1 || "Driver 1"
      }">
              <h3>${car.driver1 || "Driver 1"}</h3>
            </div>
            <div class="driver-card">
              <img src="${car.driver2_image || car.driver2Image || ""}" alt="${
        car.driver2 || "Driver 2"
      }">
              <h3>${car.driver2 || "Driver 2"}</h3>
            </div>
          </div>
        </div>
        
        <div class="detail-specs">
          <h2>Spesifikasi Teknis</h2>
          <ul>
            <li><b>Tahun</b><span>${car.year}</span></li>
            <li><b>Engine</b><span>${car.engine}</span></li>
            <li><b>Power</b><span>${car.power}</span></li>
            <li><b>Top Speed</b><span>${car.top_speed}</span></li>
            <li><b>Weight</b><span>${car.weight}</span></li>
            <li><b>Chassis</b><span>${car.chassis}</span></li>
            <li><b>Wheelbase</b><span>${car.wheelbase}</span></li>
            <li><b>Acceleration</b><span>${car.acceleration}</span></li>
            <li><b>Transmission</b><span>${car.transmission}</span></li>
            <li><b>Kapasitas Bahan Bakar</b><span>${
              car.fuel_capacity
            }</span></li>
            <li><b>Kapasitas Mesin</b><span>${car.capacity || "N/A"}</span></li>
            <li><b>RPM</b><span>${car.rpm || "N/A"}</span></li>
            <li><b>Katup</b><span>${car.valves || "N/A"}</span></li>
          </ul>
          
          <h2>Statistik</h2>
          <ul>
            <li><b>Kemenangan Balapan</b><span>${
              car.race_wins || "N/A"
            }</span></li>
            <li><b>Podium</b><span>${car.podiums || "N/A"}</span></li>
            <li><b>Poin</b><span>${car.points || "N/A"}</span></li>
            <li><b>Pembalap Utama</b><span>${car.main_drivers}</span></li>
            <li><b>Jumlah Kejuaraan</b><span>${car.championships}</span></li>
            <li><b>Pemasok Ban</b><span>${car.tire_supplier}</span></li>
          </ul>
          
          <h2>Teknologi</h2>
          <ul>
            <li><b>Aerodinamika</b><span>${car.aerodynamics}</span></li>
            <li><b>Suspensi</b><span class="suspension-style">${
              car.suspension
            }</span></li>
            <li><b>Sistem Rem</b><span>${car.brakes}</span></li>
          </ul>
          
          <a class="btn red" href="classification.php">← Kembali</a>
        </div>
      `;
    }
  }

  // ======== QUIZ (quiz.php) ==========
  // State for Quiz
  let currentQuestionIndex = 0;
  let currentScore = 0;
  let startTime;
  let timerInterval;
  let quizRunning = false;

  // 1. START QUIZ Logic
  if (startBtn && typeof quizQuestions !== "undefined") {
    startBtn.addEventListener("click", () => {
      if (quizQuestions.length === 0) {
        alert("Belum ada pertanyaan di database!");
        return;
      }

      // UI Updates
      startScreen.classList.add("hidden");
      quizInterface.classList.remove("hidden");
      document.getElementById("leaderboard-section").classList.add("hidden");

      // Logic Updates
      currentQuestionIndex = 0;
      currentScore = 0;
      quizRunning = true;

      // Start Timer
      startTime = new Date();
      timerInterval = setInterval(updateTimer, 1000);

      renderQuestion();
    });
  }

  function updateTimer() {
    if (!quizRunning) return;
    const now = new Date();
    const diff = Math.floor((now - startTime) / 1000);

    // Format MM:SS
    const minutes = Math.floor(diff / 60)
      .toString()
      .padStart(2, "0");
    const seconds = (diff % 60).toString().padStart(2, "0");
    if (timerDisplay) timerDisplay.textContent = `${minutes}:${seconds}`;
  }

// 2. RENDER QUESTION (FIXED: Mengirim Teks Jawaban, bukan Huruf A/B/C)
  function renderQuestion() {
      const q = quizQuestions[currentQuestionIndex];
      
      if(quizQuestion) quizQuestion.textContent = q.question;
      if(progressText) progressText.textContent = `Soal ${currentQuestionIndex + 1}/${quizQuestions.length}`;
      if(scoreText) scoreText.textContent = `Skor: ${currentScore}`;
      
      if(quizOptions) {
          quizOptions.innerHTML = "";
          
          // Acak Opsi
          const options = [
              { text: q.option_a },
              { text: q.option_b },
              { text: q.option_c },
              { text: q.option_d }
          ];
          
          // Shuffle array options
          for (let i = options.length - 1; i > 0; i--) {
              const j = Math.floor(Math.random() * (i + 1));
              [options[i], options[j]] = [options[j], options[i]];
          }

          options.forEach(opt => {
              const btn = document.createElement("button");
              btn.className = "quiz-option";
              btn.textContent = opt.text;
              
              // PERBAIKAN: Kirim teks (opt.text), bukan key (A/B)
              // Trim() digunakan untuk menghapus spasi tidak sengaja agar akurat
              btn.onclick = () => checkAnswer(opt.text.trim(), q.answer.trim(), btn);
              quizOptions.appendChild(btn);
          });
      }
      
      if(nextBtn) {
          nextBtn.disabled = true;
          nextBtn.onclick = nextQuestion;
      }
  }

  // 3. CHECK ANSWER (FIXED: Highlight Hijau berdasarkan Teks)
  function checkAnswer(selectedText, correctText, btnElement) {
      // Disable semua tombol
      const allOpts = quizOptions.querySelectorAll(".quiz-option");
      allOpts.forEach(b => b.disabled = true);

      // Bandingkan Teks dengan Teks
      const isCorrect = selectedText === correctText;
      
      if (isCorrect) {
          // Jika Benar: Tombol jadi Hijau
          btnElement.classList.add("correct");
          currentScore++;
      } else {
          // Jika Salah: Tombol jadi Merah
          btnElement.classList.add("incorrect");
          
          // DAN Cari tombol lain yang teksnya cocok dengan jawaban benar
          allOpts.forEach(btn => {
              if (btn.textContent.trim() === correctText) {
                  btn.classList.add("correct"); // Highlight Hijau otomatis
              }
          });
      }

      if(scoreText) scoreText.textContent = `Skor: ${currentScore}`;
      if(nextBtn) nextBtn.disabled = false;
  }
  // 4. NEXT / FINISH
  function nextQuestion() {
    currentQuestionIndex++;
    if (currentQuestionIndex < quizQuestions.length) {
      renderQuestion();
    } else {
      finishQuiz();
    }
  }

  function finishQuiz() {
    quizRunning = false;
    clearInterval(timerInterval);
    const endTime = new Date();
    const duration = Math.floor((endTime - startTime) / 1000); // dalam detik

    // UI Finish
    quizInterface.innerHTML = `
          <div style="text-align:center; padding:40px;">
              <h2 style="color:var(--primary-red); font-size:3rem;">FINISH! 🏁</h2>
              <p>Skor Akhir: <strong>${currentScore}/${quizQuestions.length}</strong></p>
              <p>Waktu: <strong>${duration} detik</strong></p>
              <p style="margin-top:20px; color:#888;">Menyimpan hasil ke Leaderboard...</p>
          </div>
      `;

    // Save to Database via AJAX
    fetch("quiz.php?action=save_score", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        score: currentScore,
        total: quizQuestions.length,
        duration: duration,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          setTimeout(() => {
            window.location.reload(); // Reload untuk lihat leaderboard
          }, 1500);
        } else {
          alert("Gagal menyimpan skor: " + data.message);
        }
      })
      .catch((err) => console.error(err));
  }
});

// --- LOGIKA LOGIN/REGISTER SLIDER ---
const authContainer = document.getElementById("auth-container");
const signUpButton = document.getElementById("signUp");
const signInButton = document.getElementById("signIn");

if (authContainer && signUpButton && signInButton) {
  signUpButton.addEventListener("click", () => {
    authContainer.classList.add("right-panel-active");
  });

  signInButton.addEventListener("click", () => {
    authContainer.classList.remove("right-panel-active");
  });
}
// --- AKHIR LOGIKA LOGIN/REGISTER SLIDER ---

// --- TOGGLE PASSWORD VISIBILITY ---
document.querySelectorAll(".toggle-pass").forEach((btn) => {
  btn.addEventListener("click", () => {
    const targetId = btn.getAttribute("data-target");
    const input = document.getElementById(targetId);
    if (!input) return;
    const isPassword = input.type === "password";
    input.type = isPassword ? "text" : "password";
    btn.textContent = isPassword ? "𓂀" : "👁";
  });
});
// --- AKHIR TOGGLE PASSWORD ---
