# Overal

Useful scripts to:

 1. Get direct video links (cusi)
 2. Search (cuss)
 3. Loop Search (cusq)

in Sibnet Video.

## cusi

Used to get direct video url by passing Sibnet watch url, example:

```sh
$ cusi 'https://video.sibnet.ru/video3560339/'
Video-Id: 3560339
Video-Title: [AnimeDub.ru] Mahou Shoujo Tokushusen Asuka - 08 [720p]
Accept-URL: https://video.sibnet.ru/v/da96b5847903abe5a45168102be049aa/3560339.mp4
Direct-URL: https://dv98.sibnet.ru/33/07/34/3307349.mp4?st=_1YRuFUYGucR3tIecVCFNA&e=1551544000&stor=8&noip=1
```

## cuss

Used to search for Sibnet watch urls, example:

```sh
$ cuss mahou
Mahou Shoujo Tokushusen Asuka 08 VOSTFR
https://video.sibnet.ru/video3560487/
Mahou Shoujo Tokushusen Asuka 08 VOSTFR
https://video.sibnet.ru/video3560487/
8 серия Девочки-волшебницы: Специальная операция / Mahou Shoujo Tokushusen Asuka русская озвучка Xelenum
https://video.sibnet.ru/video3560342/
8 серия Девочки-волшебницы: Специальная операция / Mahou Shoujo Tokushusen Asuka русская озвучка Xelenum
https://video.sibnet.ru/video3560342/
Mahou Shoujo Tokushusen Asuka 8 серия русская озвучка Xelenum
https://video.sibnet.ru/video3560340/
Mahou Shoujo Tokushusen Asuka 8 серия русская озвучка Xelenum
https://video.sibnet.ru/video3560340/
[AnimeDub.ru] Mahou Shoujo Tokushusen Asuka - 08 [720p]
https://video.sibnet.ru/video3560339/
[AnimeDub.ru] Mahou Shoujo Tokushusen Asuka - 08 [720p]
https://video.sibnet.ru/video3560339/
Девочки-волшебницы: Специальная операция / Mahou Shoujo Tokushusen Asuka 8 серия (Raw)
https://video.sibnet.ru/video3560332/
Девочки-волшебницы: Специальная операция / Mahou Shoujo Tokushusen Asuka 8 серия (Raw)
https://video.sibnet.ru/video3560332/
...
```

## cusq 

Used to search for series of videos, example 2-4 series of `Mahou Shoujo Tokushusen`:

```sh
$ cusq 'Mahou shoujo tokushusen' 2 4
Mahou Shoujo Tokushusen Asuka 2 серия русская озвучка Xelenum
https://video.sibnet.ru/video3531591/
Mahou Shoujo Tokushusen Asuka 3 серия русская озвучка Xelenum
https://video.sibnet.ru/video3536652/
Mahou Shoujo Tokushusen Asuka 4 серия русская озвучка Xelenum
https://video.sibnet.ru/video3540932/
```

## Licence

MIT

