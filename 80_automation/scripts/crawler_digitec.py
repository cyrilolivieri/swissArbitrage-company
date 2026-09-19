#!/usr/bin/env python3
"""
Crawler SwissArbitrage — extraction prix Digitec/Galaxus
Utilise requests + BeautifulSoup (pas de Selenium nécessaire pour les données structurées)
"""
import requests
from bs4 import BeautifulSoup
import json
import re
from datetime import datetime
import sys
import time

HEADERS = {
    "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36",
    "Accept-Language": "fr-CH,fr;q=0.9,en;q=0.8",
}

def extract_digitec_products(url, category_name, max_items=20):
    """Extrait les produits d'une page topliste Digitec."""
    products = []
    try:
        r = requests.get(url, headers=HEADERS, timeout=30)
        r.raise_for_status()
        soup = BeautifulSoup(r.text, "lxml")
        
        # Digitec structure: chaque produit dans un article ou div avec data-testid
        product_cards = soup.find_all("article") or soup.find_all("div", class_=re.compile("product"))
        
        for card in product_cards[:max_items]:
            # Nom du produit
            name_tag = card.find("h3") or card.find("h2") or card.find("a", class_=re.compile("title"))
            name = name_tag.get_text(strip=True) if name_tag else None
            
            # Prix
            price_tag = card.find(text=re.compile(r"CHF\s*[\d'.,]+"))
            price = None
            if price_tag:
                match = re.search(r"CHF\s*([\d'.,]+)", price_tag)
                if match:
                    price_str = match.group(1).replace("'", "").replace(",", ".")
                    try:
                        price = float(price_str)
                    except ValueError:
                        pass
            
            # URL produit
            link = card.find("a", href=True)
            product_url = "https://www.digitec.ch" + link["href"] if link and link["href"].startswith("/") else (link["href"] if link else None)
            
            if name and price and price >= 15:  # Seulement >= CHF 15
                products.append({
                    "name": name,
                    "price_chf": price,
                    "url": product_url,
                    "source": "digitec",
                    "category": category_name,
                    "date_extracted": datetime.now().isoformat(),
                })
        
        return products
    except Exception as e:
        print(f"ERREUR extraction {url}: {e}", file=sys.stderr)
        return []


def extract_galaxus_products(url, category_name, max_items=20):
    """Extrait les produits d'une page Galaxus (même structure que Digitec)."""
    products = []
    try:
        r = requests.get(url, headers=HEADERS, timeout=30)
        r.raise_for_status()
        soup = BeautifulSoup(r.text, "lxml")
        
        product_cards = soup.find_all("article") or soup.find_all("div", class_=re.compile("product"))
        
        for card in product_cards[:max_items]:
            name_tag = card.find("h3") or card.find("h2") or card.find("a", class_=re.compile("title"))
            name = name_tag.get_text(strip=True) if name_tag else None
            
            price_tag = card.find(text=re.compile(r"CHF\s*[\d'.,]+"))
            price = None
            if price_tag:
                match = re.search(r"CHF\s*([\d'.,]+)", price_tag)
                if match:
                    price_str = match.group(1).replace("'", "").replace(",", ".")
                    try:
                        price = float(price_str)
                    except ValueError:
                        pass
            
            link = card.find("a", href=True)
            product_url = "https://www.galaxus.ch" + link["href"] if link and link["href"].startswith("/") else (link["href"] if link else None)
            
            if name and price and price >= 15:
                products.append({
                    "name": name,
                    "price_chf": price,
                    "url": product_url,
                    "source": "galaxus",
                    "category": category_name,
                    "date_extracted": datetime.now().isoformat(),
                })
        
        return products
    except Exception as e:
        print(f"ERREUR extraction {url}: {e}", file=sys.stderr)
        return []


def extract_single_page(url, source, category_name):
    """Extrait un produit unique depuis sa page détail."""
    try:
        r = requests.get(url, headers=HEADERS, timeout=30)
        r.raise_for_status()
        soup = BeautifulSoup(r.text, "lxml")
        
        # Nom
        name_tag = soup.find("h1") or soup.find("h2")
        name = name_tag.get_text(strip=True) if name_tag else None
        
        # Prix — chercher dans toute la page
        price_text = soup.find(text=re.compile(r"CHF\s*[\d'.,]+"))
        price = None
        if price_text:
            match = re.search(r"CHF\s*([\d'.,]+)", price_text)
            if match:
                price_str = match.group(1).replace("'", "").replace(",", ".")
                try:
                    price = float(price_str)
                except ValueError:
                    pass
        
        if name and price:
            return {
                "name": name,
                "price_chf": price,
                "url": url,
                "source": source,
                "category": category_name,
                "date_extracted": datetime.now().isoformat(),
            }
        return None
    except Exception as e:
        print(f"ERREUR page {url}: {e}", file=sys.stderr)
        return None


if __name__ == "__main__":
    urls = [
        ("https://www.digitec.ch/en/s1/producttype/toplist/relevance/usb-cables-292", "digitec", "usb-cables"),
        ("https://www.digitec.ch/en/s1/producttype/toplist/relevance/usb-hubs-463", "digitec", "usb-hubs"),
        ("https://www.digitec.ch/en/s1/producttype/toplist/relevance/notebook-stands-2062", "digitec", "notebook-stands"),
        ("https://www.digitec.ch/en/s1/producttype/toplist/relevance/usb-chargers-463", "digitec", "usb-chargers"),
    ]
    
    all_products = []
    for url, source, category in urls:
        print(f"Extraction: {category} @ {source}", file=sys.stderr)
        if "digitec" in source:
            products = extract_digitec_products(url, category)
        else:
            products = extract_galaxus_products(url, category)
        all_products.extend(products)
        time.sleep(1)  # Respectueux
    
    # Sauvegarde
    output_path = "/home/cyril/swissArbitrage-company/10_products/candidates/digitec_extracted.json"
    import os
    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    with open(output_path, "w") as f:
        json.dump(all_products, f, indent=2, ensure_ascii=False)
    
    print(f"{len(all_products)} produits extraits -> {output_path}")
    for p in all_products:
        print(f"  {p['category']}: {p['name'][:60]}... CHF {p['price_chf']}")
