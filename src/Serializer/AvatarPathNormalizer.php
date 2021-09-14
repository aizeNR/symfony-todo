<?php

namespace App\Serializer;

use App\Entity\User;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class AvatarPathNormalizer implements ContextAwareNormalizerInterface
{
    private ObjectNormalizer $normalizer;
    private Packages $packages;
    private string $directory;

    public function __construct(Packages $packages, ObjectNormalizer $normalizer, string $directory)
    {
        $this->normalizer = $normalizer;
        $this->packages = $packages;
        $this->directory = $directory;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        if (!isset($data['avatar'])) {
            return $data;
        }

        // TODO fix url
        $data['avatar'] = $this->packages->getUrl($this->directory . $data['avatar']);

        return $data;    }
}