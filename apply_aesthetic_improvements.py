import re

css_path = 'Goodnight/public/css/style.css'

font_import = "@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');\n"

def apply_improvements(content):
    # Ajouter l'importation de la police au début
    content = font_import + content

    # Changer la police de base
    content = content.replace("--font-family-base: 'Arial', sans-serif;", "--font-family-base: 'Poppins', sans-serif;")
    content = content.replace("font-family: Arial, sans-serif;", "font-family: var(--font-family-base);")

    # Améliorer les ombres
    content = content.replace("box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);", "box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);")

    # Ajouter des transitions et des effets de survol
    content = content.replace(".bien-card {", ".bien-card {\n    transition: transform 0.3s ease, box-shadow 0.3s ease;")
    content = content + "\n.bien-card:hover {\n    transform: translateY(-5px);\n    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);\n}"

    content = content.replace(".admin-box:hover {", ".admin-box:hover {\n    transform: translateY(-8px);\n    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3);\n    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary) 100%);\n}")
    content = content.replace(".admin-box:active {", ".admin-box:active {\n    transform: translateY(-4px);\n}")

    return content

try:
    with open(css_path, 'r') as f:
        original_content = f.read()

    modified_content = apply_improvements(original_content)

    with open(css_path, 'w') as f:
        f.write(modified_content)

    print(f"Améliorations esthétiques appliquées à {css_path}")

except Exception as e:
    print(f"Une erreur est survenue: {e}")

