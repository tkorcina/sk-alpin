#!/bin/bash

wwwFolder=${1:-"www"}
lessFolder=${2:-"less"}
cssFolder=${3:-"css"}

for lessFile in $(find ./$wwwFolder/$lessFolder ! -name '_*' -name '*.less'); do
	lessFile2=${lessFile/\/$lessFolder\//\/$cssFolder\/}
	cssFile="${lessFile2/%\.less/.css}"
	echo "Processing $lessFile..."
	lessc $lessFile $cssFile
done
