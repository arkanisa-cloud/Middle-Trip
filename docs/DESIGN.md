---
version: alpha
name: MiddleTrip-design-system
description: A modern outdoor adventure and expedition design system for MiddleTrip. Built on a clean natural stone canvas paired with rich terracotta brand voltage, deep alpine forest dark surfaces, and an intuitive chromatic hiking difficulty grade classification (Grade A Emerald, Grade B Amber, Grade C Rose). Powered by Plus Jakarta Sans typography and organic capsule/pill geometry.

colors:
  primary: "#A0401C"
  primary-hover: "#883516"
  primary-active: "#732C12"
  primary-subtle: "#FDF3EE"
  ink: "#1A1D20"
  ink-heading: "#111827"
  body: "#4B5563"
  body-strong: "#374151"
  muted: "#6B7280"
  muted-soft: "#9CA3AF"
  hairline: "#E5E7EB"
  hairline-soft: "#F3F4F6"
  canvas: "#F8F9FA"
  canvas-alt: "#FAFAFA"
  surface-card: "#FFFFFF"
  surface-subtle: "#F9FAFB"
  surface-dark: "#0F172A"
  surface-forest: "#071A16"
  surface-forest-card: "#0D2721"
  surface-forest-border: "#153A32"
  surface-forest-tag: "#163830"
  on-primary: "#FFFFFF"
  on-dark: "#FFFFFF"
  on-dark-soft: "#D1D5DB"
  grade-a-bg: "#EAF5EF"
  grade-a-text: "#226848"
  grade-a-dot: "#10B981"
  grade-b-bg: "#FFF0E6"
  grade-b-text: "#B85320"
  grade-b-dot: "#F59E0B"
  grade-c-bg: "#FDECEB"
  grade-c-text: "#B92F26"
  grade-c-dot: "#F43F5E"
  success: "#047857"
  warning: "#D97706"
  error: "#DC2626"

typography:
  display-xl:
    fontFamily: '"Plus Jakarta Sans", sans-serif'
    fontSize: 54px
    fontWeight: 800
    lineHeight: 1.15
    letterSpacing: -0.03em
  display-lg:
    fontFamily: '"Plus Jakarta Sans", sans-serif'
    fontSize: 44px
    fontWeight: 800
    lineHeight: 1.2
    letterSpacing: -0.025em
  display-md:
    fontFamily: '"Plus Jakarta Sans", sans-serif'
    fontSize: 28px
    fontWeight: 800
    lineHeight: 1.25
    letterSpacing: -0.02em
  display-sm:
    fontFamily: '"Plus Jakarta Sans", sans-serif'
    fontSize: 24px
    fontWeight: 700
    lineHeight: 1.3
    letterSpacing: -0.015em
  title-lg:
    fontFamily: '"Plus Jakarta Sans", sans-serif'
    fontSize: 20px
    fontWeight: 700
    lineHeight: 1.35
    letterSpacing: -0.01em
  title-md:
    fontFamily: '"Plus Jakarta Sans", sans-serif'
    fontSize: 17px
    fontWeight: 700
    lineHeight: 1.4
    letterSpacing: -0.01em
  title-sm:
    fontFamily: '"Plus Jakarta Sans", sans-serif'
    fontSize: 15px
    fontWeight: 600
    lineHeight: 1.4
    letterSpacing: 0
  body-md:
    fontFamily: '"Plus Jakarta Sans", sans-serif'
    fontSize: 14.5px
    fontWeight: 400
    lineHeight: 1.6
    letterSpacing: 0
  body-sm:
    fontFamily: '"Plus Jakarta Sans", sans-serif'
    fontSize: 13px
    fontWeight: 400
    lineHeight: 1.55
    letterSpacing: 0
  caption:
    fontFamily: '"Plus Jakarta Sans", sans-serif'
    fontSize: 11px
    fontWeight: 600
    lineHeight: 1.4
    letterSpacing: 0.01em
  caption-uppercase:
    fontFamily: '"Plus Jakarta Sans", sans-serif'
    fontSize: 11px
    fontWeight: 700
    lineHeight: 1.4
    letterSpacing: 0.08em
  button:
    fontFamily: '"Plus Jakarta Sans", sans-serif'
    fontSize: 13.5px
    fontWeight: 600
    lineHeight: 1
    letterSpacing: 0
  nav-link:
    fontFamily: '"Plus Jakarta Sans", sans-serif'
    fontSize: 13.5px
    fontWeight: 500
    lineHeight: 1.4
    letterSpacing: 0

rounded:
  xs: 4px
  sm: 6px
  md: 8px
  lg: 12px
  xl: 16px
  2xl: 24px
  pill: 9999px
  full: 9999px

spacing:
  xxs: 4px
  xs: 8px
  sm: 12px
  md: 16px
  lg: 24px
  xl: 32px
  xxl: 48px
  section: 80px

components:
  top-nav-capsule:
    backgroundColor: "rgba(255, 255, 255, 0.95)"
    backdropFilter: "blur(12px)"
    textColor: "{colors.ink}"
    typography: "{typography.nav-link}"
    rounded: "{rounded.pill}"
    padding: 12px 24px
    border: "1px solid rgba(229, 231, 235, 0.7)"
    shadow: "0 1px 3px rgba(0, 0, 0, 0.05)"

  top-nav-transparent:
    backgroundColor: transparent
    textColor: "{colors.on-dark}"
    typography: "{typography.nav-link}"
    padding: 28px 20px 16px 20px

  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.on-primary}"
    typography: "{typography.button}"
    rounded: "{rounded.pill}"
    padding: 10px 20px
    height: 38px
    shadow: "0 1px 3px rgba(158, 57, 36, 0.3)"

  button-primary-hover:
    backgroundColor: "{colors.primary-hover}"
    textColor: "{colors.on-primary}"
    rounded: "{rounded.pill}"

  button-dark:
    backgroundColor: "{colors.surface-dark}"
    textColor: "{colors.on-dark}"
    typography: "{typography.button}"
    rounded: "{rounded.pill}"
    padding: 8px 20px
    height: 36px

  button-white:
    backgroundColor: "#FFFFFF"
    textColor: "{colors.ink-heading}"
    typography: "{typography.button}"
    rounded: "{rounded.pill}"
    padding: 8px 20px
    height: 36px
    shadow: "0 1px 2px rgba(0, 0, 0, 0.05)"

  button-fab:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.on-primary}"
    rounded: "{rounded.full}"
    size: 48px
    shadow: "0 10px 25px -5px rgba(158, 57, 36, 0.4)"

  filter-floating-bar:
    backgroundColor: "{colors.surface-card}"
    rounded: "{rounded.pill}"
    padding: 10px 14px
    border: "1px solid rgba(243, 244, 246, 0.8)"
    shadow: "0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1)"

  segmented-filter-track:
    backgroundColor: "rgba(229, 231, 235, 0.6)"
    rounded: "{rounded.pill}"
    padding: 4px

  segmented-filter-active:
    backgroundColor: "{colors.surface-card}"
    textColor: "{colors.ink-heading}"
    typography: "{typography.caption}"
    rounded: "{rounded.pill}"
    padding: 6px 16px
    shadow: "0 1px 2px rgba(0, 0, 0, 0.06)"

  segmented-filter-inactive:
    backgroundColor: transparent
    textColor: "{colors.body}"
    typography: "{typography.caption}"
    rounded: "{rounded.pill}"
    padding: 6px 16px

  trip-card:
    backgroundColor: "{colors.surface-card}"
    rounded: "{rounded.xl}"
    border: "1px solid {colors.hairline-soft}"
    shadow: "0 1px 3px rgba(0, 0, 0, 0.05)"

  trip-card-featured-large:
    backgroundColor: "{colors.surface-dark}"
    rounded: "{rounded.xl}"
    height: 420px
    textColor: "{colors.on-dark}"
    shadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1)"

  forest-classification-card:
    backgroundColor: "{colors.surface-forest-card}"
    border: "1px solid {colors.surface-forest-border}"
    rounded: "{rounded.2xl}"
    padding: 40px
    textColor: "{colors.on-dark}"
    shadow: "0 25px 50px -12px rgba(0, 0, 0, 0.25)"

  metric-stat-box:
    backgroundColor: "rgba(0, 0, 0, 0.25)"
    border: "1px solid rgba(255, 255, 255, 0.05)"
    rounded: "{rounded.xl}"
    padding: 16px

  badge-grade-a:
    backgroundColor: "{colors.grade-a-bg}"
    textColor: "{colors.grade-a-text}"
    typography: "{typography.caption}"
    rounded: "{rounded.pill}"
    padding: 4px 12px

  badge-grade-b:
    backgroundColor: "{colors.grade-b-bg}"
    textColor: "{colors.grade-b-text}"
    typography: "{typography.caption}"
    rounded: "{rounded.pill}"
    padding: 4px 12px

  badge-grade-c:
    backgroundColor: "{colors.grade-c-bg}"
    textColor: "{colors.grade-c-text}"
    typography: "{typography.caption}"
    rounded: "{rounded.pill}"
    padding: 4px 12px

  accordion-faq-card:
    backgroundColor: "{colors.surface-card}"
    border: "1px solid {colors.hairline}"
    rounded: "{rounded.xl}"
    padding: 16px 24px
    shadow: "0 1px 2px rgba(0, 0, 0, 0.04)"

  modal-dialog:
    backgroundColor: "{colors.surface-card}"
    rounded: "{rounded.2xl}"
    padding: 24px
    shadow: "0 25px 50px -12px rgba(0, 0, 0, 0.35)"

  toast-notification:
    backgroundColor: "{colors.success}"
    textColor: "{colors.on-dark}"
    rounded: "{rounded.pill}"
    padding: 12px 20px
    shadow: "0 10px 15px -3px rgba(0, 0, 0, 0.15)"
---

## Overview

MiddleTrip is an outdoor adventure and mountain expedition platform for Indonesian peaks. The design system bridges raw rugged nature with clean, refined editorial craftsmanship. Rather than leaning on typical loud, neon outdoor aesthetics or dark military survival tropes, MiddleTrip pairs an **organic natural stone canvas** (`{colors.canvas}` — #F8F9FA / `{colors.canvas-alt}` — #FAFAFA) with warm, sun-baked **terracotta brand voltage** (`{colors.primary}` — #9E3924) and an immersive **alpine deep forest surface** (`{colors.surface-forest}` — #071A16).

Typography is anchored entirely by **Plus Jakarta Sans**, utilizing clean geometric weights (from medium 500 to extrabold 800) with tight tracking on headings to evoke modern alpine equipment typography.

The system is defined by three complementary surface atmospheres:
1. **Natural Stone Canvas** (`{colors.canvas}` & `{colors.canvas-alt}`): The daylight floor for expedition catalogs, product grids, and FAQs.
2. **Alpine Forest Topographic Surface** (`{colors.surface-forest}` / `{colors.surface-forest-card}`): The nighttime mountain atmosphere with subtle contour line gradations (`topo-pattern`) for trail grade classifications and technical gear requirements.
3. **Scenic Mountain Atmosphere**: High-altitude hero imagery veiled in dark mist gradients (`linear-gradient(180deg, rgba(16, 24, 40, 0.45) 0%, rgba(16, 24, 40, 0.15) 40%, rgba(0,0,0,0.3) 100%)`).

**Key Characteristics:**
- **Terracotta Brand Voltage** (`{colors.primary}` — #9E3924): Reflects volcanic earth, summit sunrise, and trail clay. Used deliberately on all primary actions, price tags, active trail pills, and floating touchpoints.
- **Chromatic Grade Hierarchy**: An instantly recognizable trail difficulty rating system:
  - **Grade A (Pemula / Beginner)**: Emerald Green (`{colors.grade-a-bg}` / `{colors.grade-a-text}`)
  - **Grade B (Menengah / Intermediate)**: Amber Ochre (`{colors.grade-b-bg}` / `{colors.grade-b-text}`)
  - **Grade C (Ahli & Ekstrem / Expert)**: Rose Crimson (`{colors.grade-c-bg}` / `{colors.grade-c-text}`)
- **Pill & Capsule Geometry**: Heavily rounded silhouettes (`{rounded.pill}` 9999px) for search bars, navigation containers, segmented controls, badges, and CTA buttons.
- **Elevation Metric Tokens**: MDPL (Meter Di Atas Permukaan Laut) metric badges paired with stylized twin-peak icons.
- **Bento Grid Presentation**: The landing page balances an expansive 7-column hero card with stacked 5-column secondary summit cards.

---

## Colors

### Brand & Accent
- **Terracotta / Primary** (`{colors.primary}` — #9E3924): The primary brand signature. Represents expedition clay and alpine dawn. Used on primary buttons, active tabs, prices, and FAB.
- **Terracotta Hover** (`{colors.primary-hover}` — #862F1D): Hover state providing warmth and depth on interaction.
- **Terracotta Active / Pressed** (`{colors.primary-active}` — #722718): Darkened tactile response for clicks.
- **Terracotta Subtle** (`{colors.primary-subtle}` — #FDF2F0): Very soft terracotta tint for focused states and delicate badges.
- **Slate Dark** (`{colors.surface-dark}` — #0F172A): Deep slate used for auth buttons, contrast badges, and secondary dark elements.

### Alpine Forest & Dark Surfaces
- **Forest Base** (`{colors.surface-forest}` — #071A16): Deep alpine evergreen tone used as the canvas for technical trail classification.
- **Forest Card** (`{colors.surface-forest-card}` — #0D2721): Elevated container inside the forest section.
- **Forest Border** (`{colors.surface-forest-border}` — #153A32): 1px structural stroke framing forest cards.
- **Forest Tag** (`{colors.surface-forest-tag}` — #163830): Pill chip background for recommended mountain tags.

### Surface & Canvas
- **Canvas** (`{colors.canvas}` — #F8F9FA): Default page body background for catalog and expedition listings.
- **Canvas Alternate** (`{colors.canvas-alt}` — #FAFAFA): Neutral warm off-white body used on the homepage and footer.
- **Surface Card** (`{colors.surface-card}` — #FFFFFF): Pristine white elevated surfaces (trip cards, floating filter bar, modals, FAQ items).
- **Surface Subtle** (`{colors.surface-subtle}` — #F9FAFB): Subtle gray-50 used inside modal facility boxes and nested sub-panels.
- **Hairline** (`{colors.hairline}` — #E5E7EB): Standard 1px divider and border tone.
- **Hairline Soft** (`{colors.hairline-soft}` — #F3F4F6): Ultra-light boundary between card bodies and footers.

### Text & Ink
- **Ink Heading** (`{colors.ink-heading}` — #111827): Dominant title and headline color.
- **Ink Primary** (`{colors.ink}` — #1A1D20 / #1A1A1A): Body headings, modal titles, and navigation brand text.
- **Body Strong** (`{colors.body-strong}` — #374151): Lead paragraphs and active filter labels.
- **Body** (`{colors.body}` — #4B5563): Regular running copy, FAQ answers, and expedition details.
- **Muted** (`{colors.muted}` — #6B7280): Subtitles, trail descriptions, and navigation links.
- **Muted Soft** (`{colors.muted-soft}` — #9CA3AF): Footnotes, copyright, empty states, and elevation sub-labels.
- **On Primary** (`{colors.on-primary}` — #FFFFFF): Crisp white text over terracotta.
- **On Dark** (`{colors.on-dark}` — #FFFFFF): Pure white text on forest and dark surfaces.
- **On Dark Soft** (`{colors.on-dark-soft}` — #D1D5DB): Secondary text on forest surfaces.

### Difficulty Grade Semantic Palette
- **Grade A (Pemula / Beginner)**:
  - Background: `{colors.grade-a-bg}` (#EAF5EF)
  - Text / Border: `{colors.grade-a-text}` (#226848 / #276749)
  - Status Dot: `{colors.grade-a-dot}` (#10B981)
- **Grade B (Menengah / Intermediate)**:
  - Background: `{colors.grade-b-bg}` (#FFF0E6)
  - Text / Border: `{colors.grade-b-text}` (#B85320 / #92400E)
  - Status Dot: `{colors.grade-b-dot}` (#F59E0B)
- **Grade C (Ahli & Ekstrem / Expert)**:
  - Background: `{colors.grade-c-bg}` (#FDECEB)
  - Text / Border: `{colors.grade-c-text}` (#B92F26 / #9F1239)
  - Status Dot: `{colors.grade-c-dot}` (#F43F5E)

### Status & Feedback
- **Success** (`{colors.success}` — #047857): Toast confirmation banner for successful reservations.
- **Warning** (`{colors.warning}` — #D97706): Weather advisory or capacity cautions.
- **Error** (`{colors.error}` — #DC2626): Trail closure or booking alerts.

---

## Typography

### Unified Typography System (Plus Jakarta Sans)
MiddleTrip menggunakan satu jenis keluarga font geometrik modern terpadu: **Plus Jakarta Sans** di seluruh elemen aplikasi untuk menghadirkan visual outdoor yang konsisten, bersih, dan berkelas editorial:

- **Karakter**: Geometris bersih, netral, sangat nyaman dibaca untuk data padat, teks panjang, display headline, angka elevasi, maupun display harga.
- **Weights**: 400 (Regular), 500 (Medium), 600 (SemiBold), 700 (Bold), 800 (ExtraBold), 900 (Black).
- **CSS Token**: `var(--font-sans)` / `font-sans` (diterapkan sebagai font default aplikasi).

### Hierarchy

| Token | Size | Font | Weight | Line Height | Letter Spacing | Context & Usage |
|---|---|---|---|---|---|---|
| `{typography.display-xl}` | 54px | Plus Jakarta Sans | 800 | 1.15 | -0.03em | Hero headline on landing page |
| `{typography.display-lg}` | 44px | Plus Jakarta Sans | 800 | 1.20 | -0.025em | Catalog & Detail header headline ("Mt. Merbabu Expedition") |
| `{typography.display-md}` | 28px | Plus Jakarta Sans | 800 | 1.25 | -0.02em | Section titles ("Puncak Telah Menanti!", "Overview", "Elevasi & Rute") |
| `{typography.display-sm}` | 24px | Plus Jakarta Sans | 700 | 1.30 | -0.015em | Feature bento titles, Trail grade title ("Kelas A - Pemula") |
| `{typography.title-lg}` | 20px | Plus Jakarta Sans | 700 | 1.35 | -0.01em | Booking modal title, large card heading |
| `{typography.title-md}` | 17px | Plus Jakarta Sans | 700 | 1.40 | -0.01em | Brand logo text ("MiddleTrip"), catalog card titles |
| `{typography.title-sm}` | 15px | Plus Jakarta Sans | 600 | 1.40 | 0 | Metric values, small card titles ("Mt. Slamet") |
| `{typography.body-md}` | 14.5px| Plus Jakarta Sans | 400 | 1.60 | 0 | Header subtitles, modal descriptions |
| `{typography.body-sm}` | 13px | Plus Jakarta Sans | 400/500 | 1.55 | 0 | FAQ accordion copy, filter labels, card subtitles |
| `{typography.caption}` | 11px | Plus Jakarta Sans | 600 | 1.40 | +0.01em | Difficulty badges, elevation chips ("3.145 MDPL") |
| `{typography.caption-uppercase}` | 11px | Plus Jakarta Sans | 700 | 1.40 | +0.08em | Overline labels ("TOP PILIHAN KAMI", section headers) |
| `{typography.button}` | 13.5px| Plus Jakarta Sans | 600/700 | 1.00 | 0 | CTA buttons, filter switches, navigation actions |
| `{typography.nav-link}` | 13.5px| Plus Jakarta Sans | 500 | 1.40 | 0 | Header links ("Home", "Ekspedisi", "Contact") |

### Font Fallback
Jika font web sedang memuat atau offline, sistem menerapkan fallback:
`'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif`

---

## Layout

### Spacing System
MiddleTrip employs an 8-point base rhythm with dense 4px micro-increments:
- **Tokens**: `{spacing.xxs}` 4px · `{spacing.xs}` 8px · `{spacing.sm}` 12px · `{spacing.md}` 16px · `{spacing.lg}` 24px · `{spacing.xl}` 32px · `{spacing.xxl}` 48px · `{spacing.section}` 80px.
- **Section Rhythm**: Major page bands separate by 80px to 96px (`py-20`, `pt-28`).
- **Card Padding**:
  - Catalog trip card body: 20px (`p-5`)
  - Bento secondary cards: 12px (`p-3`)
  - Forest classification card: 32px to 40px (`p-8 md:p-10`)
  - Floating search bar: 10px to 12px (`p-2.5 sm:p-3`)

### Grid & Container System
- **Max Container Widths**:
  - Standard Content: `max-w-6xl` (~1152px)
  - Navigation Capsule: `max-w-5xl` (~1024px)
  - Dark Classification & Hero Search: `max-w-4xl` (~896px)
  - Editorial FAQ & Footer: `max-w-2xl` (~672px)
- **Grid Layouts**:
  - **Catalog Grid**: 3-column desktop (`lg:grid-cols-3`), 2-column tablet (`sm:grid-cols-2`), 1-column mobile (`grid-cols-1`) with 24px gap (`gap-6`).
  - **Landing Bento Grid**: 12-column split:
    - Dominant feature card: `lg:col-span-7` (420px height)
    - Stacked dual cards: `lg:col-span-5` with 16px gap (`gap-4`)

---

## Elevation & Depth

| Level | Treatment | Application |
|---|---|---|
| **Flat** | No shadow, pure canvas background | Main body floors, section subtitles |
| **Subtle Stroke** | 1px border `{colors.hairline-soft}` / `{colors.hairline}` | Trip cards, FAQ collapsed cards, input containers |
| **Capsule Float** | `bg-white/95 backdrop-blur-md` + soft border + `0 1px 3px rgba(0,0,0,0.05)` | Floating navbar capsule |
| **Elevated Pill** | `bg-white` + `0 20px 25px -5px rgba(0,0,0,0.1)` | Floating hero search bar, active filter pills |
| **Card Hover** | `shadow-md` + image zoom (`scale-105 transition-transform duration-500`) | Trip card interactive hover state |
| **Deep Atmosphere** | `{colors.surface-forest}` + `topo-pattern` SVG contour lines | Trail grade classification band |
| **Overlay Dialog** | `bg-black/60 backdrop-blur-sm` + `shadow-2xl` | Booking modal, detail inspection |
| **Floating Action (FAB)** | Terracotta fill + `0 10px 25px -5px rgba(158,57,36,0.4)` | Floating chat button (bottom-right) |

### Topographic & Decorative Details
- **Contour Wave Texture (`topo-pattern`)**: Applied over `{colors.surface-forest}` using radial ellipses that mimic topographical contour lines on mountaineering trail maps.
- **Mountain Peak Iconography**: Consistent dual-stroke mountain glyph (`M8 3l4 8 5-5 5 15H2L8 3z`) in logo headers, elevation metrics, and brand markers.
- **Active Route Underline**: 2px solid indicator positioned 4px beneath active navigation links (`after:h-[2px]`).

---

## Shapes

### Border Radius Hierarchy

| Token | Value | Application |
|---|---|---|
| `{rounded.xs}` | 4px | Micro indicator dots, tiny checkmark badges |
| `{rounded.sm}` | 6px | Small dropdown items |
| `{rounded.md}` | 8px | Tertiary inputs, inner facility chips |
| `{rounded.lg}` | 12px | Inner image containers on bento stacked cards |
| `{rounded.xl}` | 16px | Trip cards, FAQ containers, stat metric boxes |
| `{rounded.2xl}` | 24px | Forest classification card, booking detail modal |
| `{rounded.pill}` | 9999px | Buttons, search bar, segmented controls, grade pills, navbar capsule |
| `{rounded.full}` | 50% | Floating action button (48px), close icon buttons, filter reset buttons |

### Photography & Image Treatment
- **Aspect Ratios**:
  - Catalog Cards: fixed height `h-56` (~16:10 ratio) with `object-cover`.
  - Bento Hero: `h-[380px] md:h-[420px]` full bleed with gradient vignette.
  - Grade Spec Card: `aspect-[4/3]` rounded container.
- **Vignette Gradients**: Every hero/cover photograph includes an overlay gradient (`from-black/80 via-black/20 to-black/30` or `from-black/40 via-transparent`) to guarantee white text contrast under WCAG AAA standards.
- **Hover Micro-interaction**: `group-hover:scale-105 transition duration-500` on cover images.

---

## Components

### 1. Top Navigation

#### `top-nav-capsule` (Katalog & App Pages)
- **Geometry**: Pinned or floating capsule container centered with `max-w-5xl`. Rounded `{rounded.pill}`, padding 12px × 24px.
- **Surface**: `bg-white/95 backdrop-blur-md` with 1px border `rgba(229, 231, 235, 0.7)` and soft drop shadow.
- **Content**:
  - Left: MiddleTrip logo mark (mountain SVG + bold text).
  - Center: Horizontal links in `{typography.nav-link}`. Active link features a 2px dark underline.
  - Right: "Login" ghost link + "Daftar" pill button (`{colors.surface-dark}` or `{colors.primary}`).

#### `top-nav-transparent` (Landing Hero Mode)
- **Geometry**: Full width within `max-w-6xl`, transparent background layered over scenic mountain hero image.
- **Content**: White typography and logo, with "Daftar" rendered as an inverted white pill (`bg-white text-gray-900`).

### 2. Buttons & Actions

#### `button-primary`
- **Background**: `{colors.primary}` (#9E3924)
- **Hover / Active**: `{colors.primary-hover}` (#862F1D) / `{colors.primary-active}` (#722718)
- **Text**: White, `{typography.button}`
- **Radius**: `{rounded.pill}`
- **Usage**: "Cari Jalur", "Pilih Trip →", "Lanjut Reservasi"

#### `button-secondary-pill`
- **Background**: `bg-white` with 1px border `{colors.hairline}`, `{colors.body-strong}` text.
- **Usage**: "Kembali" in modals, filter dropdown trigger.

#### `button-fab`
- **Dimensions**: 48px × 48px circle, fixed at bottom-right (24px offset).
- **Background**: `{colors.primary}` with 25px glowing shadow. Hover scale `scale-105`.
- **Usage**: Quick WhatsApp / customer support dialog trigger.

### 3. Filters & Segmented Controls

#### `filter-floating-bar` (Landing Hero Filter)
- **Container**: Elevated capsule overlapping the hero bottom edge by 50% (`translate-y-1/2`).
- **Segments**: 3 interactive sections divided by 1px vertical hairline separators:
  1. *Lokasi Gunung* (Location pin icon + "Semua Gunung" + chevron)
  2. *Pilih Jalur* (Calendar/route icon + "Pilih Jalur" + chevron)
  3. *Semua Level* (Difficulty bars icon + "Semua Level" + chevron)
- **Action**: Terracotta "Cari Jalur" pill button at right edge.

#### `segmented-pill-filter` (Katalog Type Filter)
- **Track**: Muted gray capsule (`bg-gray-200/60 p-1 rounded-full`).
- **Active Pill**: Solid white background with soft shadow (`bg-white text-gray-900 shadow-sm`).
- **Inactive Pill**: Transparent background with `{colors.body}` text, hover darkening.

#### `grade-dropdown-filter`
- **Trigger**: Capsule button displaying active grade name + chevron.
- **Menu**: Elevated card (`rounded-2xl shadow-xl py-2`) featuring grade items with color-coded dot indicators (`emerald-500`, `amber-500`, `rose-500`).

### 4. Cards & Containers

#### `trip-card` (Standard Catalog Card)
- **Surface**: White card, `{rounded.xl}`, 1px hairline border.
- **Cover Image**: 224px height (`h-56`) with top-left floating grade badge pill (`{component.badge-grade-*}`).
- **Body**:
  - Title: Expedition title in `{typography.title-md}`.
  - Elevation: Mountain icon + `{typography.caption}` ("3,145 MDPL").
  - Footer divider: 1px soft hairline (`border-gray-50`).
  - Pricing: "Harga Saat Ini" label + bold formatted IDR price ("Rp 800.000 / pax").
  - CTA: Compact terracotta pill button with directional arrow ("Pilih Trip →").

#### `trip-card-featured-bento` (Landing Page Bento Grid)
- **Primary Hero Card (Span 7)**: 420px height, full-bleed mountain imagery, gradient vignette, dual badges (Grade pill + MDPL pill), large headline, and price summary.
- **Secondary Stacked Cards (Span 5)**: Compact card pair with 112px (`h-28`) image preview, title, truncated description, and terracotta price link with arrow.

### 5. Trail Grade Classification System

#### `forest-classification-card`
- **Section Floor**: Dark alpine forest (`{colors.surface-forest}`) with topographic contour lines (`topo-pattern`).
- **Switcher**: Centered capsule segmented switcher (Grade A, Grade B, Grade C).
- **Card Container**: Rich dark evergreen (`{colors.surface-forest-card}`) framed by `{colors.surface-forest-border}`.
- **Spec Breakdown**:
  - Left column: Dynamic grade title, detailed description, dual metric boxes (Durasi Trek, Kebutuhan Fisik), and recommended mountain tags.
  - Right column: Aspect 4:3 high-contrast mountain trail photograph.

### 6. Modals & Dialogs

#### `modal-dialog` (Expedition Booking & Detail Modal)
- **Overlay**: `bg-black/60 backdrop-blur-sm` fixed fullscreen.
- **Container**: Centered white card with `{rounded.2xl}` (24px) radius, max width 512px (`max-w-lg`).
- **Header**: 224px image cover with floating grade badge and circular close button (✕).
- **Body**: Title, elevation, price highlight, route overview card (`bg-gray-50 rounded-xl`), and 2-column checklist of included facilities with green checkmark glyphs.
- **Footer**: Dual actions — 1/3 outline "Kembali" pill + 2/3 terracotta "Lanjut Reservasi" pill.

### 7. Feedback & Accordions

#### `toast-notification`
- **Container**: Fixed bottom-right capsule in `{colors.success}` (#047857) with white checkmark icon and bold confirmation text.

#### `accordion-faq-card`
- **Surface**: White card with `{rounded.xl}`, 1px hairline border.
- **Header**: Question label in `{typography.body-sm}` font-semibold with rotating chevron icon (`rotate-180 transition-transform duration-200`).
- **Body**: Smooth revealed answer container with top divider border.

---

## Do's and Don'ts

### Do
- **Anchor on Natural Stone**: Use `#F8F9FA` or `#FAFAFA` for page canvas backgrounds. Pure cold gray (#F1F5F9) breaks the organic natural feel.
- **Reserve Terracotta for Action**: Apply `{colors.primary}` (#9E3924) deliberately on primary CTAs, price amounts, active filter states, and floating actions.
- **Preserve Grade Color Coding**: Always bind Grade A to Emerald, Grade B to Amber, and Grade C to Rose. Never mix or invert difficulty indicators.
- **Use Pill Geometry**: Buttons, badges, segmented controls, and search filters must adhere to `{rounded.pill}` (9999px).
- **Always Include Elevation (MDPL)**: Mountain listings must feature MDPL elevation data accompanied by the mountain peak glyph.
- **Contrast Photography with Vignettes**: Always layer text on mountain imagery over a dark gradient overlay.

### Don't
- **Don't Use Generic Cyan or Blue**: Blue makes MiddleTrip look like a corporate SaaS or generic airline booking tool. Stay true to Terracotta and Alpine Forest.
- **Don't Square Off Interactive Elements**: Avoid sharp square corners (`rounded-none` or `rounded-sm`) for buttons and badges.
- **Don't Mix Sans Typefaces**: Stick strictly to Plus Jakarta Sans. Do not mix Inter, Roboto, or serif fonts into the UI.
- **Don't Overuse Dark Forest Everywhere**: The dark forest surface is a dramatic, high-value feature reserved for technical trail classifications and hero footers. Keep standard catalog pages light and airy.
- **Don't Remove Units from Prices**: Always format prices with currency and unit (`Rp 800.000 / pax` or `Rp 800k / pax`).

---

## Responsive Behavior

### Breakpoints

| Name | Width | Key Layout Changes |
|---|---|---|
| **Mobile** | `< 640px` | Navigation collapses; search bar switches from inline pill to stacked rounded-2xl block; catalog grid becomes 1-column; bento grid stacks into single column; modal width becomes 95vw. |
| **Tablet** | `640px – 1024px` | Catalog grid displays 2 columns; search bar switches to horizontal; bento cards stack below hero card; navigation capsule becomes compact. |
| **Desktop** | `1024px – 1280px` | Full 3-column catalog grid; 7:5 bento split; floating search pill full width; complete horizontal navigation. |
| **Wide** | `> 1280px` | Content width constrained to `max-w-6xl` (1152px) centered with generous horizontal whitespace. |

### Touch Targets
- All primary CTA buttons maintain a minimum tap height of 38px to 44px.
- Floating Chat FAB is fixed at 48px × 48px.
- Segmented filter buttons allow minimum 36px touch heights with horizontal scrolling on mobile.
- Entire card surfaces in catalog grids are interactive.

---

## Iteration Guide

1. **Token First**: Always reference color tokens (`{colors.primary}`, `{colors.surface-forest}`) rather than hardcoding hex values.
2. **Grade Consistency**: When adding new trip cards or difficulty filters, verify the grade badge uses the corresponding background, text, and dot indicator token.
3. **Card Symmetry**: Ensure catalog cards keep identical image heights (`h-56`) and flexible content bodies with sticky bottom price/action footers.
4. **State Management**: Implement Default, Hover (`{colors.primary-hover}`), and Active (`{colors.primary-active}`) states for all interactive controls.
5. **Atmospheric Balance**: When designing new landing sections, balance daylight stone canvas with dark forest containers to preserve pacing.

---

## Known Gaps & Future Work

- **Interactive Datepicker**: The current route and schedule selector relies on static label triggers; an inline calendar modal matching the pill design language is slated for phase 2.
- **Interactive Topographic Map**: Static SVG contour patterns can be upgraded to an interactive Mapbox or Leaflet vector layer styled with alpine forest tones.
- **Review & Rating Badges**: Trip cards currently emphasize Grade and Elevation; a subtle star rating token (`#F59E0B`) will be integrated above pricing.
- **Multi-Day Itinerary Timeline**: A vertical trail milestone component using mountain peak icons and trail grade colors will be added for individual trip detail pages.
