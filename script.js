document.addEventListener("DOMContentLoaded", () => {
  const header = document.getElementById("siteHeader");
  const mobileMenuBtn = document.getElementById("mobileMenuBtn");
  const mobileNav = document.getElementById("mobileNav");
  const navLinks = Array.from(document.querySelectorAll(".nav-link"));
  const sections = navLinks
    .map((link) => document.getElementById(link.dataset.section))
    .filter(Boolean);

  /* ---------- Sticky Navbar & Scroll Spy ---------- */
  const updateHeaderState = () => {
    if (window.scrollY > 40) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }

    const scrollPos = window.scrollY + 180;
    let activeId = "hero";

    for (const section of sections) {
      if (section.offsetTop <= scrollPos) {
        activeId = section.id;
      }
    }

    navLinks.forEach((link) => {
      link.classList.toggle("active", link.dataset.section === activeId);
    });
  };

  window.addEventListener("scroll", updateHeaderState, { passive: true });
  updateHeaderState();

  /* ---------- Mobile Menu ---------- */
  if (mobileMenuBtn && mobileNav) {
    mobileMenuBtn.addEventListener("click", () => {
      const isHidden = mobileNav.hasAttribute("hidden");
      if (isHidden) {
        mobileNav.removeAttribute("hidden");
        mobileMenuBtn.setAttribute("aria-expanded", "true");
      } else {
        mobileNav.setAttribute("hidden", "");
        mobileMenuBtn.setAttribute("aria-expanded", "false");
      }
    });

    mobileNav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        mobileNav.setAttribute("hidden", "");
        mobileMenuBtn.setAttribute("aria-expanded", "false");
      });
    });
  }

  /* ---------- Beranda Accordion ---------- */
  const accordionItems = Array.from(document.querySelectorAll(".accordion-item"));

  accordionItems.forEach((item) => {
    const trigger = item.querySelector(".accordion-trigger");
    const panel = item.querySelector(".accordion-panel");
    const circle = item.querySelector(".accordion-circle");

    trigger.addEventListener("click", () => {
      const willOpen = !item.classList.contains("is-open");

      accordionItems.forEach((other) => {
        const otherTrigger = other.querySelector(".accordion-trigger");
        const otherPanel = other.querySelector(".accordion-panel");
        const otherCircle = other.querySelector(".accordion-circle");

        other.classList.remove("is-open");
        otherTrigger.setAttribute("aria-expanded", "false");
        otherPanel.setAttribute("hidden", "");
        otherCircle.textContent = "↗";
      });

      if (willOpen) {
        item.classList.add("is-open");
        trigger.setAttribute("aria-expanded", "true");
        panel.removeAttribute("hidden");
        circle.textContent = "↙";
      }
    });
  });

  /* ---------- Detail Modal (Ibadah, Sermon, Acara) ---------- */
  const modalBackdrop = document.getElementById("detailModal");
  const modalClose = document.getElementById("modalClose");
  const modalKicker = document.getElementById("modalKicker");
  const modalTitle = document.getElementById("modalTitle");
  const modalSubtitle = document.getElementById("modalSubtitle");
  const modalMeta = document.getElementById("modalMeta");
  const modalActions = document.getElementById("modalActions");

  const ibadahData = {
    kids: {
      kicker: "IBADAH ANAK",
      title: "Kids",
      subtitle: "Ibadah kreatif dan pengenalan firman Tuhan untuk anak usia 3–12 tahun.",
      meta: [
        ["Hari", "Minggu"],
        ["Jam", "09.00 WIB"],
        ["Lokasi", "Ruang Kids — AOCC Semarang"]
      ]
    },
    teens: {
      kicker: "IBADAH REMAJA",
      title: "Teens",
      subtitle: "Persekutuan remaja yang hangat untuk bertumbuh dalam karakter Kristus.",
      meta: [
        ["Hari", "Sabtu"],
        ["Jam", "16.00 WIB"],
        ["Lokasi", "Ruang Teens — AOCC Semarang"]
      ]
    },
    youth: {
      kicker: "IBADAH PEMUDA",
      title: "Youth",
      subtitle: "Komunitas generasi muda usia 18–30 tahun untuk memuji Tuhan dan bertumbuh bersama.",
      meta: [
        ["Hari", "Sabtu"],
        ["Jam", "17.00 WIB"],
        ["Lokasi", "Ruang Serbaguna — AOCC Semarang"]
      ]
    },
    raya: {
      kicker: "IBADAH UMUM",
      title: "Ibadah Raya",
      subtitle: "Ibadah keluarga besar AOCC Semarang dengan pujian penyembahan dan pemberitaan firman.",
      meta: [
        ["Hari", "Minggu"],
        ["Jam", "06.30, 09.00 & 17.00 WIB"],
        ["Lokasi", "Ruang Ibadah — AOCC Semarang"]
      ]
    },
    pria: {
      kicker: "PERSEKUTUAN",
      title: "Kaum Pria",
      subtitle: "Persekutuan kaum pria untuk saling menguatkan dalam iman, keluarga, dan pekerjaan.",
      meta: [
        ["Hari", "Jumat"],
        ["Jam", "16.00 WIB"],
        ["Lokasi", "Hubungi Sekretariat AOCC"]
      ]
    },
    wanita: {
      kicker: "PERSEKUTUAN",
      title: "Kaum Wanita",
      subtitle: "Persekutuan kaum wanita yang berakar dalam doa, firman, dan kasih persaudaraan.",
      meta: [
        ["Hari", "Sabtu"],
        ["Jam", "17.00 WIB"],
        ["Lokasi", "Hubungi Sekretariat AOCC"]
      ]
    }
  };

  const sermonData = [
    {
      kicker: "SERMON YOUTUBE",
      title: "Hidup yang Berpusat pada Kasih",
      subtitle: "Raja Berhati Hamba — Khotbah Ibadah Raya AOCC Semarang.",
      meta: [
        ["Pembicara", "Pdt. Henoch Edi"],
        ["Tanggal", "8 Nov 2026"],
        ["Platform", "YouTube @AOCCSemarang"]
      ],
      ctaText: "Tonton di YouTube →",
      ctaHref: "https://www.youtube.com/@AOCCSemarang"
    },
    {
      kicker: "SERMON YOUTUBE",
      title: "Iman di Tengah Badai",
      subtitle: "Worship Night — Kekuatan Dahsyat Sebuah Doa.",
      meta: [
        ["Pembicara", "Pdt. Henoch Edi"],
        ["Tanggal", "1 Nov 2026"],
        ["Platform", "YouTube @AOCCSemarang"]
      ],
      ctaText: "Tonton di YouTube →",
      ctaHref: "https://www.youtube.com/@AOCCSemarang"
    },
    {
      kicker: "SERMON YOUTUBE",
      title: "Rumah yang Dibangun di Atas Batu",
      subtitle: "Pilihlah Kehidupan (Ulangan 30:19) — Ibadah Raya AOCC Semarang.",
      meta: [
        ["Pembicara", "Pdt. Henoch Edi"],
        ["Tanggal", "26 Okt 2026"],
        ["Platform", "YouTube @AOCCSemarang"]
      ],
      ctaText: "Tonton di YouTube →",
      ctaHref: "https://www.youtube.com/@AOCCSemarang"
    }
  ];

  const eventData = [
    {
      kicker: "ACARA • 21 NOV",
      title: "Pendalaman Alkitab",
      subtitle: "Belajar firman secara mendalam bersama komunitas kecil.",
      meta: [
        ["Lokasi", "Online"],
        ["Jam", "19.00 WIB"],
        ["Pendaftaran", "Melalui form Narahubung"]
      ]
    },
    {
      kicker: "ACARA • 28 NOV",
      title: "Worship Night",
      subtitle: "Satu malam penuh penyembahan dan doa bersama.",
      meta: [
        ["Lokasi", "Gedung Utama"],
        ["Jam", "19.00 WIB"],
        ["Alamat", "Jl. Prof. Soedarto No.50, Semarang"]
      ]
    },
    {
      kicker: "ACARA • 05 DES",
      title: "Spirit Link",
      subtitle: "Pertemuan yang dipimpin Roh untuk generasi muda.",
      meta: [
        ["Lokasi", "Ruang Serbaguna"],
        ["Jam", "19.00 WIB"],
        ["Komunitas", "@youthservicekaon"]
      ]
    },
    {
      kicker: "ACARA • 12 DES",
      title: "YES Project",
      subtitle: "Proyek pelayanan dan kepedulian bagi kota kita.",
      meta: [
        ["Lokasi", "Ruang Serbaguna"],
        ["Jam", "19.00 WIB"],
        ["Kontak", "(024) 7464620"]
      ]
    }
  ];

  const openModal = ({ kicker, title, subtitle, meta, ctaText, ctaHref }) => {
    modalKicker.textContent = kicker;
    modalTitle.textContent = title;
    modalSubtitle.textContent = subtitle;
    modalMeta.innerHTML = meta
      .map(
        ([k, v]) =>
          `<div class="modal-meta-row"><strong>${k}</strong><span>${v}</span></div>`
      )
      .join("");

    modalActions.innerHTML = "";
    if (ctaHref) {
      const linkBtn = document.createElement("a");
      linkBtn.href = ctaHref;
      linkBtn.target = "_blank";
      linkBtn.rel = "noopener noreferrer";
      linkBtn.className = "pill-btn pill-btn-dark";
      linkBtn.textContent = ctaText;
      modalActions.appendChild(linkBtn);
    } else {
      const contactBtn = document.createElement("a");
      contactBtn.href = "#narahubung";
      contactBtn.className = "pill-btn pill-btn-dark";
      contactBtn.textContent = "Hubungi Kami →";
      contactBtn.addEventListener("click", closeModal);
      modalActions.appendChild(contactBtn);
    }

    modalBackdrop.removeAttribute("hidden");
    modalClose.focus();
  };

  const closeModal = () => {
    modalBackdrop.setAttribute("hidden", "");
  };

  modalClose.addEventListener("click", closeModal);
  modalBackdrop.addEventListener("click", (e) => {
    if (e.target === modalBackdrop) closeModal();
  });
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !modalBackdrop.hasAttribute("hidden")) closeModal();
  });

  document.querySelectorAll("[data-ibadah]").forEach((card) => {
    const activate = () => {
      const item = ibadahData[card.dataset.ibadah];
      if (item) openModal(item);
    };
    card.addEventListener("click", activate);
    card.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        activate();
      }
    });
  });

  document.querySelectorAll("[data-sermon]").forEach((card) => {
    const activate = () => {
      const item = sermonData[Number(card.dataset.sermon)];
      if (item) openModal(item);
    };
    card.addEventListener("click", activate);
    card.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        activate();
      }
    });
  });

  document.querySelectorAll("[data-event]").forEach((card) => {
    const activate = () => {
      const item = eventData[Number(card.dataset.event)];
      if (item) openModal(item);
    };
    card.addEventListener("click", activate);
    card.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        activate();
      }
    });
  });

  /* ---------- Contact Form Validation & Feedback ---------- */
  const contactForm = document.getElementById("contactForm");
  const formStatus = document.getElementById("formStatus");

  if (contactForm) {
    contactForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const firstName = contactForm.firstName.value.trim();
      const email = contactForm.email.value.trim();
      const message = contactForm.message.value.trim();

      formStatus.className = "form-status";

      if (!firstName || !email || !message) {
        formStatus.textContent = "Mohon lengkapi nama depan, email, dan pesan Anda.";
        formStatus.classList.add("is-error");
        return;
      }

      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        formStatus.textContent = "Format email belum valid. Mohon periksa kembali.";
        formStatus.classList.add("is-error");
        return;
      }

      formStatus.textContent = `Terima kasih, ${firstName}! Pesan Anda telah tercatat. Tim AOCC Semarang akan segera menghubungi Anda.`;
      formStatus.classList.add("is-success");
      contactForm.reset();
    });
  }
});
