# =====
# Display a colored "section" header
# =====
section_header() {
  printf "%s\n" "" "${magenta}===${normal} $1 ${magenta}===${normal}" ""
}