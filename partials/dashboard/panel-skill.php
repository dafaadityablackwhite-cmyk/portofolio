<section id="panel-skill" class="tab-panel <?= $activeTab === 'skill' ? 'active' : '' ?>">
  
  <div class="grid-2col">
    <!-- Progress Bar Skills -->
    <div class="glass-card">
      <h3 class="section-title">
        <i class="fa-solid fa-code"></i> Keahlian Pemrograman
      </h3>

      <div class="skill-category">
        <div class="skill-item">
          <div class="skill-info">
            <span>TypeScript</span>
            <span>45%</span>
          </div>
          <div class="skill-bar-bg">
            <div class="skill-bar-fill" data-progress="45%"></div>
          </div>
        </div>

        <div class="skill-item">
          <div class="skill-info">
            <span>HTML</span>
            <span>50%</span>
          </div>
          <div class="skill-bar-bg">
            <div class="skill-bar-fill" data-progress="50%"></div>
          </div>
        </div>

        <div class="skill-item">
          <div class="skill-info">
            <span>JavaScript</span>
            <span>35%</span>
          </div>
          <div class="skill-bar-bg">
            <div class="skill-bar-fill" data-progress="35%"></div>
          </div>
        </div>

        <div class="skill-item">
          <div class="skill-info">
            <span>CSS</span>
            <span>40%</span>
          </div>
          <div class="skill-bar-bg">
            <div class="skill-bar-fill" data-progress="40%"></div>
          </div>
        </div>

        <div class="skill-item">
          <div class="skill-info">
            <span>Unity / C#</span>
            <span>30%</span>
          </div>
          <div class="skill-bar-bg">
            <div class="skill-bar-fill" data-progress="30%"></div>
          </div>
        </div>

        <div class="skill-item">
          <div class="skill-info">
            <span>PHP</span>
            <span>40%</span>
          </div>
          <div class="skill-bar-bg">
            <div class="skill-bar-fill" data-progress="40%"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Target Belajar & Roadmap -->
    <div class="glass-card">
      <h3 class="section-title">
        <i class="fa-solid fa-chart-line"></i> Target & Roadmap
      </h3>

      <div class="result-box" style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px;">
          <span>Fullstack & Game Dev Progress</span>
          <span style="color: var(--accent-cyan); font-weight: 600;">50%</span>
        </div>
        <div class="skill-bar-bg">
          <div class="skill-bar-fill" data-progress="50%"></div>
        </div>
      </div>

      <ul class="history-list">
        <li>
          <i class="fa-solid fa-check-circle" style="color: #10b981;"></i>
          <div>
            <strong>Penguasaan Dasar Web</strong>
            <p>Memahami struktur HTML, CSS layout, dan aksi JavaScript sederhana.</p>
          </div>
        </li>
        <li>
          <i class="fa-solid fa-clock" style="color: #f59e0b;"></i>
          <div>
            <strong>Pengembangan Backend PHP & Database</strong>
            <p>Mempelajari integrasi PHP session, form handling, dan CRUD database MySQL.</p>
          </div>
        </li>
        <li>
          <i class="fa-solid fa-spinner fa-spin" style="color: var(--accent-cyan);"></i>
          <div>
            <strong>Eksplorasi Game Mechanic Unity</strong>
            <p>Membuat mekanik game platformer/action dasar menggunakan C#.</p>
          </div>
        </li>
      </ul>
    </div>
  </div>

</section>
