import json
import sys

def predict(data):
    pekerjaan      = str(data.get("pekerjaan", "")).lower()
    penghasilan    = float(data.get("penghasilan", 0))
    tanggungan     = int(data.get("jumlah_tanggungan", 0))
    aset           = str(data.get("aset_kepemilikan", "")).lower()
    bantuan_lain   = str(data.get("bantuan_lain", "")).lower()
    usia           = int(data.get("usia", 0))
    kondisi_rumah  = str(data.get("kondisi_rumah", "")).lower()
    meteran_listrik= str(data.get("meteran_listrik", "")).lower()
    sumber_air     = str(data.get("sumber_air", "")).lower()

    score = 0

    # Parameter lama (tidak diubah)
    if pekerjaan in ["buruh", "tidak bekerja", "petani", "nelayan"]:
        score += 0.2

    if penghasilan < 1000000:
        score += 0.3
    elif penghasilan < 2000000:
        score += 0.2

    if tanggungan >= 4:
        score += 0.2

    if "mobil" in aset:
        score -= 0.2

    if bantuan_lain == "ya":
        score -= 0.1

    if usia >= 60:
        score += 0.1

    # Parameter baru
    if kondisi_rumah == "tidak layak":
        score += 0.15
    elif kondisi_rumah == "sedang":
        score += 0.05

    if meteran_listrik == "450va":
        score += 0.1
    elif meteran_listrik == "900va":
        score += 0.05

    if sumber_air == "sungai":
        score += 0.1
    elif sumber_air == "sumur":
        score += 0.05

    probability    = max(0, min(score, 1))
    recommendation = "Layak" if probability >= 0.5 else "Tidak Layak"

    return {
        "probability":    round(probability, 4),
        "recommendation": recommendation
    }

def read_input():
    try:
        if not sys.stdin.isatty():
            raw = sys.stdin.read().strip()
            if raw:
                return json.loads(raw)
    except:
        pass

    if len(sys.argv) > 1:
        raw = sys.argv[1]
        return json.loads(raw)

    raise ValueError("No JSON input provided")

if __name__ == "__main__":
    try:
        data   = read_input()
        result = predict(data)
        print(json.dumps(result))
    except Exception as e:
        print(json.dumps({"error": str(e)}))
        sys.exit(1)