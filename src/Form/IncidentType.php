<?php

namespace App\Form;

use App\Entity\Incident;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class IncidentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $places = [
            "Tunis", "Le Bardo", "Le Kram", "La Goulette", "Carthage", "Sidi Bou Said", "La Marsa",
            "Souk Lahad", "Ariana", "La Soukra", "Raoued", "Kalâat el-Andalous", "Sidi Thabet",
            "Ettadhamen-Mnihla", "Ben Arous", "El Mourouj", "Hammam Lif", "Hammam Chott",
            "Bou Mhel el-Bassatine", "Ezzahra", "Radès", "Mégrine", "Mohamedia-Fouchana",
            "Mornag", "Khalidia", "Manouba", "Den Den", "Douar Hicher", "Oued Ellil", "Mornaguia",
            "Borj El Amri", "Djedeida", "Tebourba", "El Battan", "Nabeul", "Dar Chaabane",
            "Béni Khiar", "El Maâmoura", "Somâa", "Korba", "Tazerka", "Menzel Temime",
            "Menzel Horr", "El Mida", "Kelibia", "Azmour", "Hammam Ghezèze", "Dar Allouch",
            "El Haouaria", "Takelsa", "Soliman", "Korbous", "Menzel Bouzelfa", "Béni Khalled",
            "Zaouiet Djedidi", "Grombalia", "Bou Argoub", "Hammamet", "Zaghouan", "Zriba",
            "Bir Mcherga", "Djebel Oust", "El Fahs", "Nadhour", "Bizerte", "Sejnane", "Mateur",
            "Menzel Bourguiba", "Tinja", "Ghar al Milh", "Aousja", "Menzel Jemil",
            "Menzel Abderrahmane", "El Alia", "Ras Jebel", "Metline", "Raf Raf", "Béja",
            "El Maâgoula", "Zahret Medien", "Nefza", "Téboursouk", "Testour", "Goubellat",
            "Majaz al Bab", "Jendouba", "Bou Salem", "Tabarka", "Aïn Draham", "Fernana",
            "Beni M'Tir", "Ghardimaou", "Oued Melliz", "El Kef", "Nebeur", "Touiref",
            "Sakiet Sidi Youssef", "Tajerouine", "Menzel Salem", "Kalaat es Senam",
            "Kalâat Khasba", "Jérissa", "El Ksour", "Dahmani", "Sers", "Siliana",
            "Bou Arada", "Gaâfour", "El Krib", "Sidi Bou Rouis", "Maktar", "Rouhia",
            "Kesra", "Bargou", "El Aroussa", "Sousse", "Ksibet Thrayet", "Ezzouhour",
            "Zaouiet Sousse", "Hammam Sousse", "Akouda", "Kalâa Kebira", "Sidi Bou Ali",
            "Hergla", "Enfidha", "Bouficha", "Sidi El Hani", "M'saken", "Kalâa Seghira",
            "Messaadine", "Kondar", "Monastir", "Khniss", "Ouerdanin", "Sahline Moôtmar",
            "Sidi Ameur", "Zéramdine", "Beni Hassen", "Ghenada", "Jemmal", "Menzel Kamel",
            "Zaouiet Kontoch", "Bembla-Mnara", "Menzel Ennour", "El Masdour", "Moknine",
            "Sidi Bennour", "Menzel Farsi", "Amiret El Fhoul", "Amiret Touazra",
            "Amiret El Hojjaj", "Cherahil", "Bekalta", "Téboulba", "Ksar Hellal",
            "Ksibet El Mediouni", "Benen Bodher", "Touza", "Sayada", "Lemta", "Bouhjar",
            "Menzel Hayet", "Mahdia", "Rejiche", "Bou Merdes", "Ouled Chamekh", "Chorbane",
            "Hebira", "Essouassi", "El Djem", "Kerker", "Chebba", "Melloulèche",
            "Sidi Alouane", "Ksour Essef", "El Bradâa", "Sfax", "Sakiet Ezzit", "Chihia",
            "Sakiet Eddaïer", "Gremda", "El Ain", "Thyna", "Agareb", "Jebiniana", "El Hencha",
            "Menzel Chaker", "Ghraïba", "Bir Ali Ben Khélifa", "Skhira", "Mahares", "Kerkennah",
            "Medenine", "Beni Khedache", "Ben Gardane", "Zarzis", "Houmt El Souk (Djerba)",
            "Midoun (Djerba)", "Ajim (Djerba)", "Tataouine", "Bir Lahmar", "Ghomrassen",
            "Dehiba", "Remada", "Gafsa", "El Ksar", "Moularès", "Redeyef", "Métlaoui",
            "Mdhila", "El Guettar", "Sened", "Tozeur", "Degache", "Hamet Jerid", "Nafta",
            "Tamerza", "Kebili", "Djemna", "Douz", "El Golâa", "Souk Lahad"
        ];
        $types = [
            "Collision",
            "Vehicle Breakdown",
            "Road Debris",
            "Pothole Damage",
            "Vehicle Fire",
            "Flooding",
            "Pedestrian Accident",
            "Animal Crossing",
            "Road Construction",
            "Traffic Congestion"
        ];

        $choices = array_combine($types, $types);

        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => array_combine($types, $types)
            ])
       
           
            ->add('place',  ChoiceType::class, [
                'label' => 'Type',
                'choices' => array_combine($places, $places)
            ])
            ->add('hour', TimeType::class, [
                'label' => 'Hour',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
           
            // Ajoutez d'autres champs selon les besoins
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Incident::class,
        ]);
    }
}
