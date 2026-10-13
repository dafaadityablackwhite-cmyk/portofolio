(() => {
  'use strict';

  /* ============ Penyimpanan ============ */
  const kunci = 'webhewan-favorit';
  const simpan = {
    ambil(k) { try { return localStorage.getItem(k); } catch { return null; } },
    taruh(k, v) { try { localStorage.setItem(k, v); } catch { /* abaikan */ } }
  };

  const acak = arr => {
    const a = [...arr];
    for (let i = a.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
  };

  const kurangGerak = () =>
    window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ============ Toast ============ */
  const toast = document.getElementById('toast');
  let toastTimer;
  function pesan(teks) {
    if (!toast) return;
    clearTimeout(toastTimer);
    toast.textContent = teks;
    toast.hidden = false;
    requestAnimationFrame(() => toast.classList.add('tampil'));
    toastTimer = setTimeout(() => {
      toast.classList.remove('tampil');
      setTimeout(() => { toast.hidden = true; }, 300);
    }, 2400);
  }

  /* ============ Kumpulan kartu hewan ============ */
  const kartu = [...document.querySelectorAll('.kategori article')].map((el, i) => {
    const img = el.querySelector('.besar img');
    const fig = el.closest('.gambar-hewan') || el.querySelector('.gambar-hewan');
    return {
      el,
      fig,
      img,
      id: el.id || ('hewan-' + i),
      asli: i,
      nama: el.querySelector('h3').textContent.trim(),
      kategori: el.closest('.kategori').id,
      status: el.dataset.status || '',
      statusLabel: el.dataset.statusLabel || '',
      latin: el.dataset.latin || '',
      habitat: el.dataset.habitat || '',
      emo: el.dataset.emo || '🐾',
      src: img ? img.getAttribute('src') : '',
      alt: img ? img.getAttribute('alt') : el.querySelector('h3').textContent.trim(),
      teks: ''
    };
  });

  // Hilangkan aksen/huruf besar supaya pencarian lebih longgar
  const normal = t => (t || '').toLowerCase()
    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
    .replace(/\s+/g, ' ').trim();

  const namaKat = { karnivora: 'Karnivora', herbivora: 'Herbivora', omnivora: 'Omnivora' };

  // Indeks pencarian: nama, kategori, status, latin, habitat, dan isi teks
  kartu.forEach(k => {
    const kat = k.kategori + ' ' + (namaKat[k.kategori] || '')
      + ' pemakan ' + ({ karnivora: 'daging', herbivora: 'tumbuhan', omnivora: 'daging dan tumbuhan' }[k.kategori] || '');
    k.teks = normal([
      k.nama, kat, k.status, k.statusLabel, k.latin, k.habitat, k.el.textContent
    ].join(' '));
  });

  let favorit = new Set();
  try { favorit = new Set(JSON.parse(simpan.ambil(kunci) || '[]')); } catch { /* abaikan */ }

  function simpanFavorit() {
    simpan.taruh(kunci, JSON.stringify([...favorit]));
  }

  /* ============ Cadangan bila gambar gagal dimuat ============ */
  function pasangCadangan(k) {
    if (!k.fig || k.fig.classList.contains('isi-emoji')) return;
    if (!k.fig.querySelector('.emoji-placeholder')) {
      const ph = document.createElement('span');
      ph.className = 'emoji-placeholder';
      ph.textContent = k.emo;
      ph.setAttribute('role', 'img');
      ph.setAttribute('aria-label', 'Ilustrasi ' + k.nama);
      // letakkan di dalam tombol .besar supaya tombol tetap bisa diklik
      (k.besar || k.fig).appendChild(ph);
    }
    k.fig.classList.add('isi-emoji');
  }

  kartu.forEach(k => {
    k.besar = k.el.querySelector('.besar');
    if (!k.img) { pasangCadangan(k); return; }
    k.img.addEventListener('error', () => pasangCadangan(k));
    if (k.img.complete && k.img.naturalWidth === 0) pasangCadangan(k);
  });

  /* ============ Tombol di tiap kartu ============ */
  kartu.forEach(k => {
    const paragraf = k.el.querySelectorAll(':scope > p');
    const aksi = document.createElement('div');
    aksi.className = 'aksi';

    if (paragraf.length > 1) {
      const fakta = document.createElement('div');
      fakta.className = 'fakta';
      fakta.id = k.id + '-fakta';
      fakta.hidden = true;
      for (let i = 1; i < paragraf.length; i++) fakta.appendChild(paragraf[i]);
      k.el.appendChild(fakta);

      const buka = document.createElement('button');
      buka.type = 'button';
      buka.textContent = 'Tampilkan fakta lain';
      buka.setAttribute('aria-expanded', 'false');
      buka.setAttribute('aria-controls', fakta.id);
      buka.addEventListener('click', () => {
        const terbuka = fakta.hidden;
        fakta.hidden = !terbuka;
        buka.setAttribute('aria-expanded', String(terbuka));
        buka.textContent = terbuka ? 'Sembunyikan fakta' : 'Tampilkan fakta lain';
      });
      aksi.appendChild(buka);
    }

    const detail = document.createElement('button');
    detail.type = 'button';
    detail.className = 'lihat-detail';
    detail.textContent = 'Lihat detail';
    detail.addEventListener('click', () => bukaPanel(k, detail));
    aksi.appendChild(detail);

    const suka = document.createElement('button');
    suka.type = 'button';
    suka.className = 'suka';
    const segarkan = () => {
      const ya = favorit.has(k.id);
      suka.setAttribute('aria-pressed', String(ya));
      suka.textContent = ya ? '♥ Favorit' : '♡ Jadikan favorit';
    };
    suka.addEventListener('click', () => {
      const baru = !favorit.has(k.id);
      baru ? favorit.add(k.id) : favorit.delete(k.id);
      simpanFavorit();
      segarkan();
      sinkronPanelSuka();
      saring();
      pesan(baru ? `${k.nama} ditambahkan ke favorit` : `${k.nama} dihapus dari favorit`);
    });
    segarkan();
    k.segar = segarkan;
    aksi.appendChild(suka);

    k.el.appendChild(aksi);
  });

  /* ============ Cari + saring ============ */
  const cari = document.getElementById('cari');
  const statusEl = document.getElementById('status');
  const kosong = document.getElementById('kosong');
  const tombolFilter = [...document.querySelectorAll('button[data-filter]')];
  const tombolStatus = [...document.querySelectorAll('button[data-status]')];
  const tombolUrut = [...document.querySelectorAll('button[data-urut]')];
  let aktif = 'semua';
  let aktifStatus = 'semua';
  let urutAktif = 'kategori';

  function kartuTampil() { return kartu.filter(k => !k.el.hidden); }

  function saring() {
    const q = normal(cari.value);
    const kata = q ? q.split(' ') : [];
    let tampil = 0;

    kartu.forEach(k => {
      const cocokKat = aktif === 'semua'
        || (aktif === 'favorit' ? favorit.has(k.id) : k.kategori === aktif);
      const cocokStatus = aktifStatus === 'semua' || k.status === aktifStatus;
      const cocok = cocokKat && cocokStatus && kata.every(w => k.teks.includes(w));
      k.el.hidden = !cocok;
      if (cocok) tampil++;
    });

    document.querySelectorAll('.kategori').forEach(g => {
      g.hidden = !g.querySelector('article:not([hidden])');
    });

    const sisaFilter = (aktif !== 'semua' ? 1 : 0) + (aktifStatus !== 'semua' ? 1 : 0);
    statusEl.textContent = `Menampilkan ${tampil} dari ${kartu.length} hewan`
      + (sisaFilter || q ? ` (filter aktif${q ? ', pencarian "' + cari.value.trim() + '"' : ''})` : '');

    if (tampil === 0) {
      kosong.hidden = false;
      kosong.textContent = aktif === 'favorit' && !q && aktifStatus === 'semua'
        ? 'Belum ada hewan favorit. Tekan "Jadikan favorit" pada hewan yang kamu suka.'
        : 'Tidak ada hewan yang cocok. Ubah kata pencarian atau tekan "Semua" pada filter.';
    } else {
      kosong.hidden = true;
    }
  }

  cari.addEventListener('input', saring);

  tombolFilter.forEach(b => b.addEventListener('click', () => {
    aktif = b.dataset.filter;
    tombolFilter.forEach(x => x.setAttribute('aria-pressed', String(x === b)));
    saring();
  }));

  tombolStatus.forEach(b => b.addEventListener('click', () => {
    aktifStatus = b.dataset.status;
    tombolStatus.forEach(x => x.setAttribute('aria-pressed', String(x === b)));
    saring();
  }));

  /* ============ Pengurutan (di dalam tiap kategori) ============ */
  const bobotStatus = { kritis: 0, terancam: 1, rentan: 2, rendah: 3, '': 4 };

  function urutkan() {
    document.querySelectorAll('.kategori').forEach(grup => {
      const isi = kartu.filter(k => k.el.closest('.kategori') === grup);
      isi.sort((a, b) => {
        if (urutAktif === 'nama') return a.nama.localeCompare(b.nama, 'id');
        if (urutAktif === 'status') {
          return (bobotStatus[a.status] - bobotStatus[b.status])
            || a.nama.localeCompare(b.nama, 'id');
        }
        return a.asli - b.asli;
      });
      isi.forEach(k => grup.appendChild(k.el));
    });
    saring();
  }

  tombolUrut.forEach(b => b.addEventListener('click', () => {
    urutAktif = b.dataset.urut;
    tombolUrut.forEach(x => x.setAttribute('aria-pressed', String(x === b)));
    urutkan();
  }));

  saring();

  /* ============ Dialog detail hewan ============ */
  const panel = document.getElementById('panel-hewan');
  const panelTutup = document.getElementById('panel-tutup');
  const panelJudul = document.getElementById('panel-judul');
  const panelLatin = document.getElementById('panel-latin');
  const panelNomor = document.getElementById('panel-nomor');
  const panelStatus = document.getElementById('panel-status');
  const panelKategori = document.getElementById('panel-kategori');
  const panelHabitat = document.getElementById('panel-habitat');
  const panelTeks = document.getElementById('panel-teks');
  const panelGambar = document.getElementById('panel-gambar');
  const panelImg = document.getElementById('panel-img');
  const petunjukZoom = document.getElementById('petunjuk-zoom');
  const panelSuka = document.getElementById('panel-suka');
  const panelPrev = document.getElementById('panel-prev');
  const panelNext = document.getElementById('panel-next');
  const petunjukKeys = document.querySelector('.petunjuk-keys');

  let panelKartu = null;
  let pemicu = null;

  function isiPanel(k) {
    panelKartu = k;
    panelJudul.textContent = k.nama;
    panelLatin.textContent = k.latin ? '(' + k.latin + ')' : '';
    panelStatus.textContent = k.statusLabel || 'Belum diketahui';
    panelStatus.className = 'lencana ' + k.status;
    panelKategori.textContent = k.kategori
      ? k.kategori.charAt(0).toUpperCase() + k.kategori.slice(1) : '';
    panelHabitat.textContent = k.habitat || '—';

    const daftar = kartuTampil().length ? kartuTampil() : kartu;
    panelNomor.textContent = `Hewan ${daftar.indexOf(k) + 1} dari ${daftar.length}`;
    panelPrev.disabled = daftar.length < 2;
    panelNext.disabled = daftar.length < 2;
    if (petunjukKeys) petunjukKeys.hidden = daftar.length < 2;

    panelTeks.innerHTML = '';
    const sumber = k.el.querySelectorAll('.fakta p, :scope > p');
    if (sumber.length) {
      [...sumber].forEach(p => {
        const salinan = document.createElement('p');
        salinan.innerHTML = p.innerHTML;
        panelTeks.appendChild(salinan);
      });
    }

    panelGambar.setAttribute('aria-pressed', 'false');
    panelGambar.setAttribute('aria-label', 'Perbesar gambar ' + k.nama);
    if (petunjukZoom) petunjukZoom.hidden = false;

    // Pakai emoji langsung bila foto kartunya sudah gagal dimuat
    const rusak = !k.src || (k.fig && k.fig.classList.contains('isi-emoji'));
    if (rusak) {
      panelEmoji();
    } else {
      panelEmoji(true);
      panelImg.hidden = false;
      panelImg.src = k.src;
      panelImg.alt = k.alt;
    }
    sinkronPanelSuka();
  }

  function panelEmoji(hapus) {
    let lama = panelGambar.querySelector('.penyamar');
    if (hapus) { if (lama) lama.remove(); panelImg.hidden = false; return; }
    panelImg.hidden = true;
    if (!lama) {
      lama = document.createElement('span');
      lama.className = 'penyamar';
      lama.setAttribute('role', 'img');
      panelGambar.appendChild(lama);
    }
    lama.textContent = panelKartu ? panelKartu.emo : '🐾';
    lama.setAttribute('aria-label', panelKartu ? 'Ilustrasi ' + panelKartu.nama : 'Ilustrasi hewan');
    if (petunjukZoom) petunjukZoom.hidden = true;
  }

  panelImg.addEventListener('error', () => panelEmoji());

  function bukaPanel(k, dari) {
    pemicu = dari || k.img || null;
    const ph = panelGambar.querySelector('.penyamar');
    if (ph) ph.remove();
    isiPanel(k);
    if (typeof panel.showModal === 'function') {
      if (!panel.open) panel.showModal();
    } else {
      panel.setAttribute('open', '');
    }
    panelTutup.focus();
  }

  function tutupPanel() {
    if (typeof panel.close === 'function' && panel.open) panel.close();
    else panel.removeAttribute('open');
  }

  function gerakPanel(arah) {
    const daftar = kartuTampil().length ? kartuTampil() : kartu;
    if (daftar.length < 2 || !panelKartu) return;
    const i = daftar.indexOf(panelKartu);
    const berikut = daftar[(i + arah + daftar.length) % daftar.length];
    isiPanel(berikut);
  }

  function sinkronPanelSuka() {
    if (!panelKartu || !panelSuka) return;
    const ya = favorit.has(panelKartu.id);
    panelSuka.setAttribute('aria-pressed', String(ya));
    panelSuka.textContent = ya ? '♥ Favorit' : '♡ Jadikan favorit';
  }

  document.querySelectorAll('[data-detail]').forEach(b => {
    const k = kartu.find(x => x.id === b.dataset.detail);
    if (k) b.addEventListener('click', () => bukaPanel(k, b));
  });

  panelGambar.addEventListener('click', () => {
    const besar = panelGambar.getAttribute('aria-pressed') === 'true';
    panelGambar.setAttribute('aria-pressed', String(!besar));
    panelGambar.setAttribute('aria-label',
      (besar ? 'Perbesar gambar ' : 'Kembalikan ukuran gambar ') + (panelKartu ? panelKartu.nama : ''));
  });

  panelTutup.addEventListener('click', tutupPanel);
  panelPrev.addEventListener('click', () => gerakPanel(-1));
  panelNext.addEventListener('click', () => gerakPanel(1));

  panelSuka.addEventListener('click', () => {
    if (!panelKartu) return;
    const baru = !favorit.has(panelKartu.id);
    baru ? favorit.add(panelKartu.id) : favorit.delete(panelKartu.id);
    simpanFavorit();
    kartu.forEach(k => k.segar && k.segar());
    sinkronPanelSuka();
    saring();
    pesan(baru ? `${panelKartu.nama} ditambahkan ke favorit` : `${panelKartu.nama} dihapus dari favorit`);
  });

  panel.addEventListener('click', e => {
    if (e.target === panel) tutupPanel();
  });

  panel.addEventListener('close', () => {
    if (pemicu && document.contains(pemicu)) pemicu.focus();
  });

  /* ============ Gambar di kuis ============ */
  const kotakGambar = document.getElementById('kotak-gambar');

  function tampilkanGambar(wadah, k, label) {
    wadah.innerHTML = '';
    const img = document.createElement('img');
    img.alt = label;
    img.decoding = 'async';
    img.src = k.src;
    const gagal = () => {
      wadah.innerHTML = '';
      const s = document.createElement('span');
      s.className = 'penyamar';
      s.textContent = k.emo;
      s.setAttribute('role', 'img');
      s.setAttribute('aria-label', label);
      wadah.appendChild(s);
    };
    img.addEventListener('error', gagal);
    wadah.appendChild(img);
    if (img.complete && img.naturalWidth === 0) gagal();
  }

  /* ============ Kuis ============ */
  const soal = document.getElementById('soal');
  const umpan = document.getElementById('umpan');
  const skorEl = document.getElementById('skor');
  const lanjut = document.getElementById('lanjut');
  const kotakKuis = document.getElementById('kotak-kuis');
  const barSoal = document.getElementById('bar-soal');
  const barIsi = barSoal ? barSoal.querySelector('span') : null;
  const pilihan = document.getElementById('pilihan');
  const jawaban = [...document.querySelectorAll('[data-jawab]')];
  const namaKategori = { karnivora: 'Karnivora', herbivora: 'Herbivora', omnivora: 'Omnivora' };

  let urutan = [], nomor = 0, benar = 0, beruntun = 0, terbaik = 0, sekarang = null, selesai = false;

  function aturBar(persen) {
    if (barIsi) barIsi.style.width = persen + '%';
    if (barSoal) barSoal.setAttribute('aria-valuenow', String(Math.round(persen)));
  }

  function acakJawaban() {
    acak(jawaban).forEach(b => pilihan.appendChild(b));
  }

  function mulai(pesanMulai) {
    urutan = acak(kartu);
    selesai = false;
    nomor = 0;
    benar = 0;
    beruntun = 0;
    terbaik = 0;
    acakJawaban();
    tampilkanSoal();
    if (pesanMulai) pesan('Kuis dimulai. Semangat!');
  }

  function tampilkanSoal() {
    sekarang = urutan[nomor];
    soal.textContent = `Soal ${nomor + 1} dari ${urutan.length}: ${sekarang.nama} termasuk golongan apa?`;
    umpan.textContent = '';
    umpan.className = '';
    skorEl.textContent = `Skor: ${benar}  ·  Beruntun: ${beruntun}`;
    lanjut.hidden = true;
    jawaban.forEach(b => {
      b.disabled = false;
      b.removeAttribute('data-kunci');
      b.setAttribute('aria-label', b.textContent);
    });
    aturBar((nomor / urutan.length) * 100);
    if (kotakGambar) {
      kotakGambar.hidden = false;
      tampilkanGambar(kotakGambar, sekarang, 'Gambar hewan soal ' + (nomor + 1));
    }
  }

  jawaban.forEach(b => b.addEventListener('click', () => {
    const tepat = b.dataset.jawab === sekarang.kategori;
    const kunci = jawaban.find(x => x.dataset.jawab === sekarang.kategori);
    if (kunci) kunci.dataset.kunci = 'benar';
    if (!tepat) b.dataset.kunci = 'salah';

    if (tepat) {
      benar++;
      beruntun++;
      terbaik = Math.max(terbaik, beruntun);
    } else {
      beruntun = 0;
    }

    umpan.className = tepat ? 'benar' : 'salah';
    umpan.textContent = tepat
      ? `Benar! ${sekarang.nama} termasuk ${namaKategori[sekarang.kategori]}.`
      : `Belum tepat. ${sekarang.nama} termasuk ${namaKategori[sekarang.kategori]}.`;

    skorEl.textContent = `Skor: ${benar}  ·  Beruntun terpanjang: ${terbaik}`;
    jawaban.forEach(x => (x.disabled = true));
    aturBar(((nomor + 1) / urutan.length) * 100);
    lanjut.hidden = false;
    lanjut.textContent = nomor + 1 < urutan.length ? 'Soal berikutnya' : 'Lihat hasil';
    lanjut.focus();
  }));

  lanjut.addEventListener('click', () => {
    if (selesai) { mulai(true); return; }
    nomor++;
    if (nomor < urutan.length) { tampilkanSoal(); return; }

    soal.textContent = `Selesai! Skor akhir kamu ${benar} dari ${urutan.length}.`;
    umpan.textContent = benar === urutan.length
      ? 'Sempurna! Semua jawabanmu benar!'
      : (benar >= urutan.length * 0.7
        ? 'Kerja bagus, hampir semua jawabanmu benar!'
        : 'Coba lagi ya, semakin sering berlatih semakin mudah!');
    umpan.className = benar === urutan.length ? 'benar' : '';
    skorEl.textContent = `Beruntun terpanjang: ${terbaik}`;
    jawaban.forEach(b => (b.disabled = true));
    aturBar(100);
    if (kotakGambar) kotakGambar.hidden = true;
    selesai = true;
    lanjut.textContent = 'Main lagi';
    if (benar === urutan.length) pesan('🎉 Sempurna! Skor 10 dari 10!');
  });

  mulai();

  /* ============ Pintasan papan ketik ============ */
  document.addEventListener('keydown', e => {
    const t = e.target;
    const mengetik = t && (t.tagName === 'INPUT' || t.tagName === 'TEXTAREA' || t.isContentEditable);
    if (e.key === '/' && !mengetik) {
      e.preventDefault();
      cari.focus();
      cari.select();
      return;
    }
    if (panel && panel.open) {
      if (e.key === 'ArrowLeft') { e.preventDefault(); gerakPanel(-1); }
      if (e.key === 'ArrowRight') { e.preventDefault(); gerakPanel(1); }
    }
  });

  /* ============ Bar kemajuan membaca + tombol ke atas + sorot menu ============ */
  const barBaca = document.getElementById('bar-baca');
  const keAtas = document.getElementById('ke-atas');
  const bagianMenu = [...document.querySelectorAll('nav a')]
    .map(a => ({ a, el: document.querySelector(a.getAttribute('href')) }))
    .filter(x => x.el);

  let menunggu = false;
  function segarkanGulir() {
    const doc = document.documentElement;
    const maks = doc.scrollHeight - window.innerHeight;
    const persen = maks > 0 ? (window.scrollY / maks) * 100 : 0;
    if (barBaca) {
      barBaca.style.width = persen + '%';
      barBaca.setAttribute('aria-valuenow', String(Math.round(persen)));
    }
    if (keAtas) keAtas.hidden = window.scrollY < 420;

    const garis = 150;
    let aktifEl = null;
    bagianMenu.forEach(x => { if (x.el.getBoundingClientRect().top <= garis) aktifEl = x; });
    bagianMenu.forEach(x => x.a.classList.toggle('aktif', x === aktifEl));
    menunggu = false;
  }

  window.addEventListener('scroll', () => {
    if (!menunggu) {
      menunggu = true;
      requestAnimationFrame(segarkanGulir);
    }
  }, { passive: true });
  window.addEventListener('resize', segarkanGulir);
  segarkanGulir();

  if (keAtas) {
    keAtas.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: kurangGerak() ? 'auto' : 'smooth' });
      const awal = document.querySelector('nav a') || cari;
      if (awal) awal.focus({ preventScroll: true });
    });
  }

  /* ============ Animasi masuk saat di-scroll ============ */
  if ('IntersectionObserver' in window && !kurangGerak()) {
    const target = [
      ...document.querySelectorAll('article'),
      ...document.querySelectorAll('#alasan li'),
      ...document.querySelectorAll('.kotak-kuis')
    ];
    target.forEach(el => el.classList.add('masuk'));

    const amati = new IntersectionObserver((entries, obs) => {
      entries.forEach(en => {
        if (!en.isIntersecting) return;
        const el = en.target;
        el.classList.add('tampil');
        obs.unobserve(el);
        setTimeout(() => el.classList.remove('masuk', 'tampil'), 700);
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    target.forEach(el => amati.observe(el));
  }
})();
