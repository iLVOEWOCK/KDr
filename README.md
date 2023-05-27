# KDR

Adds a simple KDR intergration system

# Commands

`/kdr` - View your KDR Statistics\
`/stats <player>` - View a players KDR Statstics\

# Config

```# KDR Plugin Configuration File
form-ui: false
# Tags: {kdr} {kills} {deaths} {player}
ui:
  stats:
    title: "Player Stats"
    content: "{player}'s Stats:\nKDR: {kdr}\nKills: {kills}\nDeaths: {deaths}"
    close-button: "Close"
  kdr:
    title: "KDR"
    content: "§6Your kill/death ratio is {kdr} (Kills: {kills}, Deaths: {deaths})."
    close-button: "Close"
messages:
  kdr: "§aYour kill/death ratio is {kdr} (Kills: {kills}, Deaths: {deaths})."
  stats: "§6{player}'s Statistics: KDR: {kdr} (Kills: {kills}, Deaths: {deaths})."
players:
  wockst4rz:
    kills: 0
    deaths: 0

```

DELETE OLD CONFIG! (js save
