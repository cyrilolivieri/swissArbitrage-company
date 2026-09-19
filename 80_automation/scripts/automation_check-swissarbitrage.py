#!/usr/bin/env python3
"""check-swissarbitrage.py — Classification enforcer for swissArbitrage-company.

Scans the workspace for:
- Files outside allowed directories
- Malformed filenames (missing date prefix, illegal chars, unknown domain)
- Product dossiers in live/ that are incomplete (< 20 files from §10)
- Scripts in 80_automation/ that don't follow domain_descriptor.py pattern

Exit codes:
  0 = all OK (silent)
  1 = violations found (prints to stdout)
"""

import os
import re
import sys
from pathlib import Path

WORKSPACE = Path("/home/cyril/swissArbitrage-company")

# Allowed top-level directories
ALLOWED_DIRS = {
    "00_admin", "10_products", "20_suppliers", "30_finance",
    "40_assets", "50_site", "60_orders", "70_customers",
    "80_automation", "90_archive", "CLASSIFICATION_RULES.md",
    ".git", ".gitignore", "README.md", ".DS_Store", "check-swissarbitrage.sh"
}

# Controlled vocabularies
DOMAINS = {"admin", "product", "supplier", "finance", "asset", "site", "order", "customer", "automation", "legal"}
STATUSES = {"draft", "final", "archived", "rejected"}

# Required files in a product dossier (from §10)
PRODUCT_FILES = {
    "01_family.md", "02_candidate-name.md", "03_competitor.md",
    "04_competitor-shipping.md", "05_supplier-primary.md", "06_supplier-backup.md",
    "07_landed-cost.md", "08_selling-price.md", "09_shipping-presentation.md",
    "10_undercut-proof.md", "11_margin-proof.md", "12_stress-test.md",
    "13_weight-dimensions.md", "14_return-risk.md", "15_compatibility-risk.md",
    "16_compliance-safety.md", "17_support-burden.md", "18_keywords.md",
    "19_seo-meta.md", "20_verdict.md"
}

# Patterns
ISO_DATE = re.compile(r"^\d{4}-\d{2}-\d{2}")
ILLEGAL_CHARS = re.compile(r'[~!@#$%^&*();:<>?,{}\'"|]')


def error(path: Path, msg: str):
    print(f"[VIOLATION] {path}: {msg}")


def check_filename(name: str, parent: Path) -> list[str]:
    """Return list of violations for a single filename."""
    violations = []
    # Skip hidden files, directories, and certain files
    if name.startswith(".") or name.endswith("/"):
        return violations
    if name in {"README.md", "CLASSIFICATION_RULES.md", ".gitignore", ".DS_Store"}:
        return violations

    # Check illegal characters
    if ILLEGAL_CHARS.search(name):
        violations.append(f"illegal characters in filename")

    # Check spaces
    if " " in name:
        violations.append(f"spaces in filename")

    # Skip template files inside product dossiers
    rel = parent.relative_to(WORKSPACE)
    parts = rel.parts
    if len(parts) >= 2 and parts[0] == "10_products" and parts[1] in {"candidates", "live", "rejected"}:
        # Inside a product dossier: files should be NN_descriptor.md
        if not re.match(r"^\d{2}_[a-z0-9\-]+\.md$", name):
            violations.append(f"product dossier file must match NN_descriptor.md pattern")
        return violations

    # Skip template directory itself
    if len(parts) >= 2 and parts[0] == "10_products" and parts[1] == "_template":
        if not re.match(r"^\d{2}_[a-z0-9\-]+\.md$", name):
            violations.append(f"template file must match NN_descriptor.md pattern")
        return violations

    # Skip scripts in 80_automation/scripts/
    if len(parts) >= 2 and parts[0] == "80_automation" and parts[1] == "scripts":
        if name.endswith(".py") and not re.match(r"^[a-z0-9\-]+_[a-z0-9\-]+\.py$", name):
            violations.append(f"script must match domain_descriptor.py pattern")
        elif name.endswith(".sh") and not re.match(r"^[a-z0-9\-]+_[a-z0-9\-]+\.sh$", name):
            violations.append(f"shell script must match domain_descriptor.sh pattern")
        return violations

    # Skip config files in 80_automation/rules/
    if len(parts) >= 2 and parts[0] == "80_automation" and parts[1] == "rules":
        return violations

    # General files: must start with ISO date
    if not ISO_DATE.match(name):
        violations.append(f"missing YYYY-MM-DD prefix")
    else:
        # Extract domain from filename: YYYY-MM-DD_domain_...
        rest = name[11:]  # after date_
        if "_" not in rest:
            violations.append(f"missing domain field after date")
        else:
            domain = rest.split("_")[0]
            if domain not in DOMAINS:
                violations.append(f"unknown domain '{domain}', must be one of: {DOMAINS}")

    return violations


def check_product_dossier(dossier: Path):
    """Check that a product dossier in candidates/live/rejected has all 20 files."""
    violations = []
    files = {f.name for f in dossier.iterdir() if f.is_file()}
    missing = PRODUCT_FILES - files
    if missing:
        violations.append(f"missing {len(missing)} required file(s): {sorted(missing)}")
    extra = files - PRODUCT_FILES - {".DS_Store"}
    if extra:
        violations.append(f"unexpected extra file(s): {sorted(extra)}")
    return violations


def main():
    violations_found = False

    for item in WORKSPACE.iterdir():
        # Check top-level dirs/files
        if item.is_dir() and item.name not in ALLOWED_DIRS:
            error(item, "directory not in allowed list")
            violations_found = True
        elif item.is_file() and item.name not in ALLOWED_DIRS:
            error(item, "file not in allowed list")
            violations_found = True

        # Recursive scan
        if item.is_dir():
            for root, dirs, files in os.walk(item):
                root_path = Path(root)
                rel = root_path.relative_to(WORKSPACE)
                parts = rel.parts

                # Check product dossiers in candidates/live/rejected
                if (len(parts) >= 3 and parts[0] == "10_products"
                        and parts[1] in {"candidates", "live", "rejected"}
                        and root_path.name != parts[1]):  # inside a specific product dir
                    dossier_violations = check_product_dossier(root_path)
                    for v in dossier_violations:
                        error(root_path, v)
                        violations_found = True
                    continue  # skip individual file checks inside product dossiers

                for fname in files:
                    # Skip hidden files
                    if fname.startswith("."):
                        continue
                    fpath = root_path / fname
                    file_violations = check_filename(fname, root_path)
                    for v in file_violations:
                        error(fpath, v)
                        violations_found = True

    if violations_found:
        print(f"\n--- check-swissarbitrage: {violations_found} violation(s) found ---")
        sys.exit(1)
    else:
        # Silent on success
        sys.exit(0)


if __name__ == "__main__":
    main()
