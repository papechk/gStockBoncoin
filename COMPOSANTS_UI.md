# Guide d'utilisation des composants UI

Cette documentation décrit tous les composants UI réutilisables disponibles dans l'application gStockBoncoin.

## 🎨 Composants de base

### Button
Bouton stylisé avec variantes et états de chargement.

```vue
<Button 
    variant="primary"  <!-- primary, secondary, danger, success, ghost -->
    size="md"          <!-- sm, md, lg -->
    :loading="isLoading"
    :disabled="false"
>
    Enregistrer
</Button>
```

### Badge
Badge pour afficher des statuts ou des compteurs.

```vue
<Badge variant="success" :dot="true">
    Actif
</Badge>
<!-- Variantes: default, success, danger, warning, info, purple -->
```

### Card
Carte avec en-tête, contenu et pied de page optionnels.

```vue
<Card title="Titre de la carte">
    <template #header>
        <div class="flex items-center justify-between">
            <h3>Titre personnalisé</h3>
            <Button size="sm">Action</Button>
        </div>
    </template>
    
    Contenu de la carte
    
    <template #footer>
        Pied de page
    </template>
</Card>
```

### StatCard
Carte de statistiques avec icône, valeur, tendance et design premium.

```vue
<StatCard
    title="Ventes du jour"
    subtitle="Comparé à hier"
    :value="156"
    format="currency"    <!-- number, currency, percentage -->
    variant="indigo"     <!-- indigo, purple, green, red, yellow, blue -->
    :trend="12.5"        <!-- Pourcentage positif ou négatif -->
>
    <template #footer>
        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800">
            Voir détails →
        </a>
    </template>
</StatCard>
```

## 📝 Composants de formulaire

### FormInput
Champ de saisie avec label, icône, validation et hint.

```vue
<FormInput
    id="nom"
    label="Nom du produit"
    v-model="form.nom"
    placeholder="Entrez le nom"
    :required="true"
    :error="form.errors.nom"
    hint="Le nom doit être unique"
/>
```

### FormSelect
Liste déroulante avec options.

```vue
<FormSelect
    id="categorie"
    label="Catégorie"
    v-model="form.categorie_id"
    :options="categories"
    placeholder="Choisir une catégorie"
    :required="true"
    :error="form.errors.categorie_id"
/>

<!-- Format des options -->
const categories = [
    { value: 1, label: 'Électronique' },
    { value: 2, label: 'Vêtements' }
];
```

### FormTextarea
Zone de texte multiligne.

```vue
<FormTextarea
    id="description"
    label="Description"
    v-model="form.description"
    :rows="4"
    placeholder="Décrivez le produit..."
    :error="form.errors.description"
/>
```

## 🪟 Composants de dialogue

### Modal
Fenêtre modale personnalisable.

```vue
<Modal
    :show="showModal"
    title="Ajouter un produit"
    subtitle="Remplissez les informations"
    @close="showModal = false"
>
    <template #icon>
        <svg>...</svg>
    </template>
    
    Contenu du formulaire
    
    <template #footer>
        <Button @click="showModal = false" variant="secondary">
            Annuler
        </Button>
        <Button @click="submit" :loading="form.processing">
            Enregistrer
        </Button>
    </template>
</Modal>
```

### ConfirmDialog
Dialogue de confirmation pour actions critiques.

```vue
<ConfirmDialog
    :show="showConfirm"
    title="Supprimer le produit ?"
    message="Cette action est irréversible. Voulez-vous continuer ?"
    type="danger"         <!-- danger, warning, info, success -->
    confirmText="Supprimer"
    cancelText="Annuler"
    :loading="isDeleting"
    @confirm="handleDelete"
    @cancel="showConfirm = false"
/>
```

## 📊 Composants de données

### DataTable
Table de données avec en-tête, corps et pied de page personnalisables.

```vue
<DataTable
    :columns="columns"
    :data="produits"
    emptyMessage="Aucun produit trouvé"
>
    <template #header>
        <div class="flex justify-between items-center">
            <h2>Liste des produits</h2>
            <Button>Ajouter</Button>
        </div>
    </template>
    
    <!-- Cellule personnalisée -->
    <template #cell-prix="{ row, value }">
        <span class="font-bold">{{ value }} MAD</span>
    </template>
    
    <template #cell-actions="{ row }">
        <Button size="sm" @click="edit(row)">Modifier</Button>
    </template>
    
    <template #footer>
        <Pagination :links="produits.links" />
    </template>
</DataTable>

<!-- Format des colonnes -->
const columns = [
    { key: 'nom', label: 'Nom', cellClass: 'font-medium' },
    { key: 'prix', label: 'Prix' },
    { key: 'stock', label: 'Stock' },
    { key: 'actions', label: 'Actions', headerClass: 'text-right' }
];
```

### Pagination
Navigation de pagination pour tables.

```vue
<Pagination
    :links="produits.links"
    :from="produits.from"
    :to="produits.to"
    :total="produits.total"
/>
```

## 🎯 Composants de navigation

### Tabs
Onglets avec navigation et panneaux de contenu.

```vue
<Tabs :tabs="tabs" default-tab="general" @change="handleTabChange">
    <TabPanel id="general">
        Contenu de l'onglet général
    </TabPanel>
    
    <TabPanel id="stock">
        Contenu de l'onglet stock
    </TabPanel>
    
    <TabPanel id="historique">
        Contenu de l'onglet historique
    </TabPanel>
</Tabs>

<!-- Format des tabs -->
const tabs = [
    { 
        id: 'general', 
        label: 'Informations générales',
        badge: null 
    },
    { 
        id: 'stock', 
        label: 'Stock',
        badge: '5' 
    },
    { 
        id: 'historique', 
        label: 'Historique',
        badge: null 
    }
];
```

## 🔔 Composants de notification

### Toast
Notification temporaire en bas à droite.

```vue
<Toast
    :show="showToast"
    type="success"      <!-- success, error, warning, info -->
    title="Succès"
    message="Le produit a été ajouté avec succès"
    :duration="5000"    <!-- ms, 0 = pas de fermeture auto -->
    @close="showToast = false"
/>
```

### Alert
Alerte inline dans le contenu.

```vue
<Alert 
    type="warning"      <!-- success, error, warning, info -->
    title="Stock faible"
    :dismissible="true"
    @dismiss="hideAlert"
>
    <p>5 produits ont un stock inférieur au seuil d'alerte.</p>
    <a href="#" class="font-semibold underline">Voir la liste</a>
</Alert>
```

## ⏳ Composants utilitaires

### Loading
Indicateur de chargement.

```vue
<!-- Spinner simple -->
<Loading 
    size="md"           <!-- sm, md, lg -->
    variant="primary"   <!-- primary, secondary, white -->
    message="Chargement en cours..."
/>

<!-- Plein écran -->
<Loading 
    :show="isLoading"
    :fullScreen="true"
    size="lg"
    message="Chargement des données..."
/>
```

## 🎨 Exemples d'utilisation

### Formulaire complet

```vue
<template>
    <form @submit.prevent="submit">
        <Card title="Nouveau produit">
            <FormInput
                id="nom"
                label="Nom"
                v-model="form.nom"
                :error="form.errors.nom"
                required
            />
            
            <FormSelect
                id="categorie"
                label="Catégorie"
                v-model="form.categorie_id"
                :options="categories"
                :error="form.errors.categorie_id"
                required
            />
            
            <FormInput
                id="prix"
                label="Prix"
                type="number"
                v-model="form.prix"
                :error="form.errors.prix"
                required
            />
            
            <FormTextarea
                id="description"
                label="Description"
                v-model="form.description"
                :error="form.errors.description"
            />
            
            <template #footer>
                <div class="flex justify-end gap-3">
                    <Button variant="secondary" @click="cancel">
                        Annuler
                    </Button>
                    <Button 
                        type="submit" 
                        :loading="form.processing"
                    >
                        Enregistrer
                    </Button>
                </div>
            </template>
        </Card>
    </form>
    
    <Toast
        :show="showToast"
        :type="toastType"
        :message="toastMessage"
        @close="showToast = false"
    />
</template>
```

### Dashboard avec statistiques

```vue
<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard
            title="Ventes du jour"
            :value="stats.ventesJour"
            format="currency"
            variant="indigo"
            :trend="stats.trendVentes"
        />
        
        <StatCard
            title="Produits en stock"
            :value="stats.produitsStock"
            format="number"
            variant="purple"
        />
        
        <StatCard
            title="Clients actifs"
            :value="stats.clientsActifs"
            format="number"
            variant="green"
            :trend="stats.trendClients"
        />
        
        <StatCard
            title="Alertes stock"
            :value="stats.alertesStock"
            format="number"
            variant="red"
        />
    </div>
    
    <Alert 
        v-if="stats.alertesStock > 0"
        type="warning"
        title="Attention"
        class="mt-6"
    >
        {{ stats.alertesStock }} produit(s) nécessite(nt) un réapprovisionnement.
    </Alert>
</template>
```

## 🎯 Bonnes pratiques

1. **Validation** : Toujours utiliser `:error` avec les composants de formulaire
2. **Loading** : Utiliser `:loading` sur les boutons lors des soumissions
3. **Confirmation** : Utiliser `ConfirmDialog` pour les actions de suppression
4. **Feedback** : Utiliser `Toast` pour confirmer les actions réussies
5. **Accessibilité** : Les composants incluent déjà les attributs ARIA nécessaires

## 🚀 Prochaines étapes

Ces composants sont prêts à être utilisés dans toutes les pages de l'application. Pour les intégrer :

1. Importer le composant nécessaire
2. Utiliser les props et événements documentés
3. Personnaliser avec les slots si nécessaire

Bonne utilisation ! 🎉
