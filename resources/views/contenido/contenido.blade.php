@extends('plantilla')
@section('contenido')
<template v-if="menu==0">
<h2>visa del menu 0</h2>
</template>
<template v-if="menu==1">
    <categoria></categoria>
</template>
<template v-if="menu==2">
    <h2>visa del menu 2</h2>
</template>
@endsection