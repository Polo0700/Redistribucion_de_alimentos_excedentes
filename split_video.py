"""Recorta un video en partes de 7 minutos usando ffmpeg (via imageio-ffmpeg)."""
import math
import subprocess
from pathlib import Path

import imageio_ffmpeg

SRC = Path(r"D:\Recording 2026-10-14 173911.mp4")
OUT = SRC.parent / "partes_7min"
CHUNK = 7 * 60  # segundos

FFMPEG = imageio_ffmpeg.get_ffmpeg_exe()


def get_duration(src: Path) -> float:
    """Lee la duración del stream de video sin redecodificar."""
    p = subprocess.run(
        [FFMPEG, "-i", str(src)],
        capture_output=True, text=True, encoding="utf-8", errors="replace",
    )
    for token in p.stderr.replace(",", " ").split():
        if ":" in token and token.count(":") == 2:
            h, m, s = token.split(":")
            if h.isdigit() and m.isdigit():
                try:
                    return int(h) * 3600 + int(m) * 60 + float(s)
                except ValueError:
                    continue
    raise RuntimeError("No se pudo leer la duración")


def main() -> None:
    OUT.mkdir(exist_ok=True)
    duration = get_duration(SRC)
    n = math.ceil(duration / CHUNK)
    print(f"Duración: {duration:.1f}s -> {n} partes")

    for i in range(n):
        start = i * CHUNK
        length = min(CHUNK, duration - start)
        dst = OUT / f"parte_{i + 1:02d}.mp4"
        cmd = [
            FFMPEG, "-y",
            "-ss", f"{start:.3f}",
            "-i", str(SRC),
            "-t", f"{length:.3f}",
            "-c:v", "libx264", "-preset", "veryfast", "-crf", "20",
            "-c:a", "aac", "-b:a", "192k",
            str(dst),
        ]
        print(f"[{i + 1}/{n}] {start/60:.1f} min -> {dst.name}")
        subprocess.run(cmd, check=True, capture_output=True)

    print(f"Listo: {OUT}")


if __name__ == "__main__":
    main()
